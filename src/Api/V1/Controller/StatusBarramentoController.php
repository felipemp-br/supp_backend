<?php

declare(strict_types=1);
/**
 * /src/Controller/StatusBarramentoController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @method StatusBarramentoResource getResource()
 */
#[Route(path: '/v1/administrativo/status_barramento')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'StatusBarramento')]
class StatusBarramentoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        StatusBarramentoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/sincroniza_barramento',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function sincronizaBarramentoAction(
        Request $request,
        int $id,
        array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $processo = $this->getResource()->sincronizaBarramento($id, $transactionId);
            $this->transactionManager->commit($transactionId);

            return new JsonResponse(
                [
                    'id' => $processo->getId(),
                    'nup' => $processo->getNUP(),
                ]
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to consultar tramites do barramento.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/consultar_tramites_barramento',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function consultarTramitesBarramento(
        Request $request,
        int $id,
        array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $tramitesBarramento = $this->getResource()->consultaTramitesBarramento($id, $transactionId);

            return new JsonResponse(
                [
                    $tramitesBarramento,
                ]
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
