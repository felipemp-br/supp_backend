<?php /** @noinspection RouteAttributeNamespaceDeprecated */

declare(strict_types=1);
/**
 * /src/Controller/ModalidadeNaturezaJuridicaController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace  SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions as AdministrativoActions;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNaturezaJuridicaResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ModalidadeNaturezaJuridicaResource getResource()
 */
#[Route(path: '/v1/administrativo/modalidade_natureza_juridica')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ModalidadeNaturezaJuridica')]
class ModalidadeNaturezaJuridicaController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Root\CreateAction;
    use Actions\Root\UpdateAction;
    use Actions\Root\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    /** @noinspection MagicMethodsValidityInspection */

    /**
     * ModalidadeNaturezaJuridicaController constructor.
     */
    public function __construct(
        ModalidadeNaturezaJuridicaResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }
}
