<?php

declare(strict_types=1);
/**
 * /src/Controller/VinculacaoMenuCoordenadorController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoMenuCoordenadorResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method VinculacaoMenuCoordenadorResource getResource()
 */
#[Route(path: '/v1/administrativo/vinculacao_menu_coordenador')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'VinculacaoMenuCoordenador')]
class VinculacaoMenuCoordenadorController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;

    public function __construct(
        VinculacaoMenuCoordenadorResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
