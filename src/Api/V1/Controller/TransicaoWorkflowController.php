<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoWorkflowResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class TransicaoWorkflowController.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 *
 * @method TransicaoWorkflowResource getResource()
 */
#[Route(path: '/v1/administrativo/transicao_workflow')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'TransicaoWorkflow')]
class TransicaoWorkflowController extends Controller
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
        TransicaoWorkflowResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
