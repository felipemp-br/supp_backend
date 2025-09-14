<?php

declare(strict_types=1);
/**
 * /src/Rest/Describer/Rest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Describer;

use OpenApi\Annotations as OA;
use SuppCore\AdministrativoBackend\Rest\Doc\RouteModel;

/**
 * Class Rest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rest
{
    final public const COUNT_ACTION = 'countAction';
    final public const CREATE_ACTION = 'createAction';
    final public const DELETE_ACTION = 'deleteAction';
    final public const FIND_ACTION = 'findAction';
    final public const FIND_ONE_ACTION = 'findOneAction';
    final public const SEARCH_ACTION = 'searchAction';
    final public const IDS_ACTION = 'idsAction';
    final public const PATCH_ACTION = 'patchAction';
    final public const UPDATE_ACTION = 'updateAction';

    private readonly Tags $tags;

    private readonly Security $security;

    private readonly Summary $summary;

    private readonly Response $response;

    private readonly Parameters $parameters;

    public function __construct(
        Tags $tags,
        Security $security,
        Summary $summary,
        Response $response,
        Parameters $parameters
    ) {
        $this->tags = $tags;
        $this->security = $security;
        $this->summary = $summary;
        $this->response = $response;
        $this->parameters = $parameters;
    }

    public function createDocs(OA\Operation $operation, RouteModel $routeModel): void
    {
        $this->tags->process($operation, $routeModel);
        $this->security->process($operation, $routeModel);
        $this->summary->process($operation, $routeModel);
        $this->response->process($operation, $routeModel);
        $this->parameters->process($operation, $routeModel);
    }
}
