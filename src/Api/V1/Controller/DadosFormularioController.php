<?php

declare(strict_types=1);
/**
 * /src/Controller/DadosFormularioController.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method DadosFormularioResource getResource()
 */
#[Route(path: '/v1/administrativo/dados_formulario')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'DadosFormulario')]
class DadosFormularioController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Colaborador\CountAction;
    use Actions\Root\CreateAction;
    use Actions\Root\UpdateAction;
    use Actions\Root\PatchAction;
    use Actions\Root\DeleteAction;

    /**
     * DadosFormularioController constructor.
     *
     * @param DadosFormularioResource $resource
     * @param ResponseHandler         $responseHandler
     */
    public function __construct(
        DadosFormularioResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
