<?php

declare(strict_types=1);
/**
 * /src/Controller/FormularioController.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method FormularioResource getResource()
 */
#[Route(path: '/v1/administrativo/formulario')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Formulario')]
class FormularioController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Colaborador\CountAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Admin\DeleteAction;

    /**
     * FormularioController constructor.
     *
     * @param FormularioResource $resource
     * @param ResponseHandler    $responseHandler
     */
    public function __construct(
        FormularioResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    #[Route(
        path: '/ia/{id}/fields',
        requirements: [
            'documentoId' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function iaFormularioFields(
        Request $request,
        int $id
    ): Response {
        $allowedHttpMethods ??= ['GET'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        try {
            $result = $this->getResource()->getFormularioJsonSchemaFields($id);
            return new Response(
                $this->getResponseHandler()->getSerializer()->serialize(
                    $result,
                    'json',
                ),
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }
}
