<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Response.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use OpenApi\Annotations as OA;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;
use Symfony\Component\DependencyInjection\ContainerInterface;

use function in_array;

/**
 * Class Response.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Response
{
    private readonly ContainerInterface $container;

    private readonly Responses $responses;

    public function __construct(ContainerInterface $container, Responses $responses)
    {
        $this->container = $container;
        $this->responses = $responses;
    }

    public function process(OA\Operation $operation, RouteModel $routeModel): void
    {
        [$action, $description, $statusCode, $responses] = $this->getDefaults($routeModel);

        if (in_array(
            $action,
            [Rest::COUNT_ACTION, Rest::FIND_ACTION, Rest::FIND_ONE_ACTION, Rest::IDS_ACTION],
            true
        )
        ) {
            $this->processResponseForRead($action, $description, $responses);
        } elseif (in_array(
            $action,
            [Rest::CREATE_ACTION, Rest::DELETE_ACTION, Rest::PATCH_ACTION, Rest::UPDATE_ACTION],
            true
        )
        ) {
            $this->processResponseForWrite($action, $description, $statusCode, $responses);
        }

        $this->processResponse($operation, $routeModel, $description, $statusCode, $responses);
    }

    /**
     * @param string[] $responses
     */
    private function processResponseForRead(
        string $action,
        string &$description,
        array &$responses
    ): void {
        if (Rest::COUNT_ACTION === $action) {
            $description = 'Count of (%s) entities';
        } elseif (Rest::FIND_ACTION === $action) {
            $description = 'Array of fetched entities (%s)';
        } elseif (Rest::FIND_ONE_ACTION === $action) {
            $description = 'Fetched entity (%s)';
            $responses[] = 'add404';
        } elseif (Rest::IDS_ACTION === $action) {
            $description = 'Fetched entities (%s) primary key values';
        }
    }

    /**
     * @param string[] $responses
     */
    private function processResponseForWrite(
        string $action,
        string &$description,
        int &$statusCode,
        array &$responses
    ): void {
        if (Rest::CREATE_ACTION === $action) {
            $description = 'Created new entity (%s)';
            $statusCode = 201;
        } elseif (Rest::DELETE_ACTION === $action) {
            $description = 'Deleted entity (%s)';
            $responses[] = 'add404';
        } elseif (Rest::PATCH_ACTION === $action) {
            $description = 'Patched entity (%s)';
            $responses[] = 'add404';
        } elseif (Rest::UPDATE_ACTION === $action) {
            $description = 'Updated entity (%s)';
            $responses[] = 'add404';
        }
    }

    /**
     * @param string[] $responses
     */
    private function processResponse(
        OA\Operation $operation,
        RouteModel $routeModel,
        string $description,
        int $statusCode,
        array $responses
    ): void {
        if (!empty($description) && $this->container->has($routeModel->getController())) {
            /** @var Controller $controller */
            $controller = $this->container->get($routeModel->getController());

            $this->responses->addOk($operation, $description, $statusCode, $controller->getResource()->getEntityName());

            foreach ($responses as $method) {
                $this->responses->{$method}($operation, $routeModel);
            }
        }
    }

    /**
     * @return mixed[]
     */
    private function getDefaults(RouteModel $routeModel): array
    {
        return [
            $routeModel->getMethod(),
            '',
            200,
            [],
        ];
    }
}
