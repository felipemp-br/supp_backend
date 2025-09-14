<?php

declare(strict_types=1);
/**
 * /src/Controller/InteressadoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use PHPUnit\Logging\Exception;
use SuppCore\AdministrativoBackend\Api\V1\Resource\InteressadoResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\Routing\Annotation\Route;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use function PHPUnit\Framework\throwException;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method InteressadoResource getResource()
 */
#[Route(path: '/v1/administrativo/interessado')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Interessado')]
class InteressadoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Colaborador\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        InteressadoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception|Throwable
     */
    #[Route(
        path: '/bloco',
        methods: ['POST']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function blocoAction(Request $request): JsonResponse
    {
        $pessoaBloco = $request->get('pessoaBloco');
        $modalidadeInteressado = $request->get('modalidadeInteressado');
        $processo = $request->get('processo');

        if(!$modalidadeInteressado) {
            throw new Exception("Modalidade de Interessado não preenchida");
        }

        $bloco = $this->getResource()->processaBloco($pessoaBloco, $modalidadeInteressado, $processo, $this->transactionManager);

        return new JsonResponse(
            [
                'status' => 'ok',
                'result' => $bloco
            ]
        );
    }
}
