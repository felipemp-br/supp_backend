<?php

declare(strict_types=1);
/**
 * /src/Controller/DadosFormularioAnonimoController.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Exception;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfigModuloResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method DadosFormularioAnonimoResource getResource()
 */
#[Route(path: '/v1/administrativo/dados_formulario_anonimo')]
#[OA\Tag(name: 'DadosFormularioAnonimo')]
class DadosFormularioAnonimoController extends Controller
{
    // Traits
    use Actions\Anon\CreateAction;
    use Actions\Anon\UpdateAction;
    use Actions\Anon\PatchAction;

    /**
     * DadosFormularioAnonimoController constructor.
     *
     * @param DadosFormularioResource $resource
     * @param ResponseHandler         $responseHandler
     * @param ConfigModuloResource    $configModuloResource
     */
    public function __construct(
        DadosFormularioResource $resource,
        ResponseHandler $responseHandler,
        private readonly ConfigModuloResource $configModuloResource
    ) {
        $this->init($resource, $responseHandler);
    }

    #[Route(
        path: '/{formulario}/{documento}/answers',
        methods: ['GET']
    )]
    #[RestApiDoc]
    public function formularioAnswers(
        Request $request,
        int $formulario,
        int $documento
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);
        try {
            $result = $this->getResource()->getRepository()->findAnswersByFormularioDocumento($formulario, $documento);
            return new Response(
                $this->getResponseHandler()->getSerializer()->serialize(
                    $result,
                    'json',
                ),
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $formulario);
        }
    }

    #[Route(
        path: '/{formulario}/{documento}/max_answers_reached',
        methods: ['GET']
    )]
    #[RestApiDoc]
    public function maxAnswersReached(
        Request $request,
        int $formulario,
        int $documento
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);
        try {
            $configModuloName = 'supp_core.consultivo_backend.qrcode.avaliacao';
            $configModulo = $this->configModuloResource->findOneBy(
                [
                    'nome' => $configModuloName,
                ]
            );
            if (!$configModulo) {
                throw new Exception("Config Modulo {$configModuloName} não foi encontrado.");
            }
            $dataValue = json_decode($configModulo->getDataValue(), true);
            
            $configModuloQtdMaxRespostas = $dataValue['quantidadeMaximaRespostas'];

            $qtdAnswersByForm = $this->getResource()->getRepository()->findAnswersByFormularioDocumento($formulario, $documento);

            return new Response(
                $this->getResponseHandler()->getSerializer()->serialize(
                    ($qtdAnswersByForm >= $configModuloQtdMaxRespostas) ? true : false,
                    'json',
                ),
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $formulario);
        }
    }
}
