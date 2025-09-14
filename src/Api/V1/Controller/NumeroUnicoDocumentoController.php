<?php

declare(strict_types=1);
/**
 * /src/Controller/NumeroUnicoDocumentoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoDocumentoResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method NumeroUnicoDocumentoResource getResource()
 */
#[Route(path: '/v1/administrativo/numero_unico_documento')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'NumeroUnicoDocumento')]
class NumeroUnicoDocumentoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\CoordenadorSetor\CreateAction;
    use Actions\CoordenadorSetor\UpdateAction;
    use Actions\CoordenadorSetor\PatchAction;
    use Actions\CoordenadorSetor\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        NumeroUnicoDocumentoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
