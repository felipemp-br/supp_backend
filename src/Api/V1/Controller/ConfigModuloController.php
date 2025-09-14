<?php

declare(strict_types=1);
/**
 * /src/Controller/ConfigModuloController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Exception;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfigModuloResource;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ConfigModuloResource getResource()
 */
#[Route(path: '/v1/administrativo/config_modulo')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ConfigModulo')]
class ConfigModuloController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CountAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Root\DeleteAction;

    public function __construct(
        ConfigModuloResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action schema.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     *
     * @noinspection PhpUnused
     */
    #[Route(
        path: '/schema/{schema_name}',
        requirements: [
            'schema_name' => '.+',
        ],
        methods: ['GET']
    )]
    public function schemaAction(
        Request $request,
        string $schema_name,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            /** @var ConfigModulo $configModulo */
            $configModulo = $this->resource->getRepository()->findOneBy(
                [
                    'nome' => $schema_name,
                ]
            );
            if (!$configModulo) {
                throw new Exception("Config Modulo {$schema_name} não foi encontrado.");
            }

            $retorno = $configModulo->getDataSchema();

            return new JsonResponse(json_decode($retorno, true, 512, JSON_THROW_ON_ERROR));
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e);
        }
    }
}
