<?php

declare(strict_types=1);
/**
 * /src/Controller/ModalidadeAcaoEtiquetaController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeAcaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @method ModalidadeAcaoEtiquetaResource getResource()
 */
#[Route(path: '/v1/administrativo/modalidade_acao_etiqueta')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ModalidadeAcaoEtiqueta')]
class ModalidadeAcaoEtiquetaController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Admin\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        ModalidadeAcaoEtiquetaResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
