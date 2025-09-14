<?php

declare(strict_types=1);
/**
 * /src/Controller/VinculacaoOrgaoCentralMetadadosController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoOrgaoCentralMetadadosResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method VinculacaoOrgaoCentralMetadadosResource getResource()
 */
#[Route(path: '/v1/administrativo/vinculacao_orgao_central_metadados')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'VinculacaoOrgaoCentralMetadados')]
class VinculacaoOrgaoCentralMetadadosController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\CoordenadorOrgaoCentral\CreateAction;
    use Actions\CoordenadorOrgaoCentral\UpdateAction;
    use Actions\CoordenadorOrgaoCentral\PatchAction;
    use Actions\CoordenadorOrgaoCentral\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        VinculacaoOrgaoCentralMetadadosResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
