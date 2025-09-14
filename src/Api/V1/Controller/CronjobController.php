<?php

declare(strict_types=1);
/**
 * /src/Controller/CronjobController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use DateTime;
use OpenApi\Attributes as OA;
use Redis;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CronjobResource as ApiResource;
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
 * @method ApiResource getResource()
 */
#[Route(path: '/v1/administrativo/cronjob')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Cronjob')]
class CronjobController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;
    use Actions\User\CountAction;

    public function __construct(
        ApiResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint shortcut action to create or get chat.
     *
     * @throws Throwable
     */
    #[
        Route(
            '/{id}/start_job',
            requirements: [
                'id' => '\d+',
            ],
            methods: ['PATCH']
        ),
        IsGranted('ROLE_ADMIN'),
    ]
    #[RestApiDoc]
    public function startJob(int $id, Request $request): Response
    {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
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

            $cronJobDTO = $this->resource->getDtoForEntity(
                $id,
                $this->resource->getDtoClass()
            );

            $cronJob = $this
                ->getResource()
                ->startJob($id, $cronJobDTO, $transactionId);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $cronJob);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint para atualizar última modificação de um cronjob no redis.
     *
     * @throws Throwable
     */
    #[
        Route(
            '/last_cronjob_update_time',
            methods: ['PUT']
        ),
        IsGranted('ROLE_ADMIN'),
    ]
    #[RestApiDoc]
    public function lastCronjobUpdateTime(Request $request, Redis $redis): Response
    {
        try {
            $timestamp = (new DateTime('now'))->getTimestamp();
            $redis->set('last_cronjob_update_time', $timestamp);

            return new JsonResponse(['timestamp' => $timestamp], 200);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
