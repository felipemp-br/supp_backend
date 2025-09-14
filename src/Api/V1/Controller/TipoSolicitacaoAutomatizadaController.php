<?php

declare(strict_types=1);
/**
 * /src/Controller/TipoSolicitacaoAutomatizadaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoSolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method TipoSolicitacaoAutomatizadaResource getResource()
 */
#[Route(path: '/v1/administrativo/tipo_solicitacao_automatizada')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'TipoSolicitacaoAutomatizada')]
class TipoSolicitacaoAutomatizadaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        TipoSolicitacaoAutomatizadaResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
