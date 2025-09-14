<?php

declare(strict_types=1);
/**
 * /src/Controller/GptController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Controller;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid as Ruuid;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource as Resource;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Message\AssistenteMessage;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Helpers\ConfigModuloTriagemHelper;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInterface;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemManager;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * Class GptController.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/inteligencia_artificial')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class InteligenciaArtificialController extends Controller
{
    /**
     * @noinspection MagicMethodsValidityInspection
     */
    public function __construct(
        Resource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action para o assistente de IA.
     *
     * @throws Throwable
     */
    #[Route(path: '/streamed', methods: ['POST'])]
    #[IsGranted('ROLE_COLABORADOR')]
    public function streamedAssistentAction(
        Request $request,
        LoggerInterface $logger,
        TokenStorageInterface $tokenStorage
    ): Response {
        $allowedHttpMethods = ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        try {
            $transactionId = $this->transactionManager->begin();
            $response = new JsonResponse();
            $uuid = Ruuid::uuid4()->toString();
            $this->transactionManager->addAsyncDispatch(
                new AssistenteMessage(
                    $tokenStorage->getToken()->getUserIdentifier(),
                    $uuid,
                    $request->get('userPrompt'),
                    $request->get('actionPrompt'),
                    $request->get('documento') ? (int)$request->get('documento') : null,
                    $request->get('rag', false),
                    $request->get('contexto', []),
                    $request->get('persona'),
                ),
                $transactionId
            );

            $response->setContent(
                json_encode([
                    'uuid' => $uuid,
                ])
            );
            $this->transactionManager->commit($transactionId);

            return $response;
        } catch (Throwable $e) {
            $logger->critical(
                '[Inteligencia Artificial] Erro ao processar chamada do asistente de inteligência artificial.',
                [
                    'error' => $e,
                ]
            );
            throw $this->handleRestMethodException($e);
        }
    }

    #[Route(
        path: '/triagem/{documentoUuid}/{force}',
        requirements: [
            'force' => 'true|false',
        ],
        defaults: ['force' => 'false'],
        methods: ['POST']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function executaTriagem(
        Request $request,
        ConfigModuloTriagemHelper $configModuloTriagemHelper,
        DocumentoResource $documentoResource,
        string $documentoUuid,
        string $force
    ): Response {
        $booleanForce = filter_var($force, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        $allowedHttpMethods ??= ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        try {
            $documento = $documentoResource->findOneBy([
                'uuid' => $documentoUuid,
            ]);
            if (!$documento) {
                throw new NotFoundHttpException();
            }
            $transactionId = $this->transactionManager->begin();
            $configModuloTriagemHelper->checkAndDispatchTriagemMessage(
                $documento,
                $transactionId,
                $booleanForce,
                true
            );
            $this->transactionManager->commit($transactionId);
            return new JsonResponse(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e);
        }
    }

    #[Route(
        path: '/triagem/{documentoId}/formularios',
        requirements: [
            'documentoId' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function getFormularios(
        Request $request,
        DocumentoRepository $documentoRepository,
        TrilhaTriagemManager $trilhasTriagemManager,
        int $documentoId,
    ): Response {
        $allowedHttpMethods ??= ['GET'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        $documento = $documentoRepository->findOneBy(['id' => $documentoId]);
        if (!$documento) {
            throw new NotFoundHttpException();
        }
        try {
            $silaTipoDoc = $documento
                ->getDocumentoIAMetadata()
                ?->getTipoDocumentoPredito()
                ?->getSigla() ?? $documento->getTipoDocumento()->getSigla();
            $formularios = array_map(
                fn(TrilhaTriagemInterface $triagem) => $triagem->getFormulario(),
                array_filter(
                    $trilhasTriagemManager->getTrilhasTriagem(),
                    fn (TrilhaTriagemInterface $trilhaTriagem) => $trilhaTriagem->suportaTipoDocumento($silaTipoDoc)
                )
            );

            return $this->getResponseHandler()
                ->createResponse(
                    $request,
                    [
                        'entities' => $formularios,
                        'total' => count($formularios),
                    ]
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $documentoId);
        }
    }

    #[Route(
        path: '/assistente/config',
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function statusAssistente(
        SuppParameterBag $suppParameterBag
    ): Response {
        try {
            $configs = [
                'ativo' => false,
            ];
            if ($suppParameterBag->has('supp_core.administrativo_backend.ia.assistente')) {
                $configs = $suppParameterBag->get('supp_core.administrativo_backend.ia.assistente');
            }

            return new JsonResponse(json_encode($configs), json: true);
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e);
        }
    }
}
