<?php

declare(strict_types=1);
/**
 * /src/Controller/AvaliacaoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AvaliacaoResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions as AdministrativoActions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method AvaliacaoResource getResource()
 */
#[Route(path: '/v1/administrativo/avaliacao')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Avaliacao')]
class AvaliacaoController extends Controller
{
    // Traits
    use AdministrativoActions\User\FindOneAction;
    use AdministrativoActions\User\FindAction;
    use AdministrativoActions\Colaborador\CreateAction;
    use AdministrativoActions\Colaborador\UpdateAction;
    use AdministrativoActions\Colaborador\PatchAction;
    use AdministrativoActions\Root\DeleteAction;
    use AdministrativoActions\User\CountAction;

    public function __construct(
        AvaliacaoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
