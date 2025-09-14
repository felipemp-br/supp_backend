<?php

declare(strict_types=1);
/**
 * /src/Controller/GeneroRelevanciaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroRelevanciaResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method GeneroRelevanciaResource getResource()
 */
#[Route(path: '/v1/administrativo/genero_relevancia')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'GeneroRelevancia')]
class GeneroRelevanciaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Root\CreateAction;
    use Actions\Root\UpdateAction;
    use Actions\Root\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        GeneroRelevanciaResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
