<?php

declare(strict_types=1);
/**
 * /src/Controller/ObjetoAvaliadoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use JMS\Serializer\Exception\LogicException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ObjetoAvaliadoResource;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions as AdministrativoActions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\Form\FormFactoryInterface;
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
 * @method ObjetoAvaliadoResource getResource()
 */
#[Route(path: '/v1/administrativo/objeto_avaliado')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'ObjetoAvaliado')]
class ObjetoAvaliadoController extends Controller
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
        ObjetoAvaliadoResource $resource,
        ResponseHandler $responseHandler,
        private readonly ObjetoAvaliadoResource $objetoAvaliadoResource
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to consultar objeto avaliado.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(path: '/consultar', methods: ['POST'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function consultaMethod(
        Request $request,
        FormFactoryInterface $formFactory,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $context = RequestHandler::getContext($request);

        try {
            $transactionId = $this->transactionManager->begin();

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $dto = $this->processFormMapper($request, self::METHOD_CREATE);

            // verifica a existência da entity no repository
            $data = $this
                ->objetoAvaliadoResource
                ->findOneBy(
                    [
                        'classe' => $dto->getClasse(),
                        'objetoId' => $dto->getObjetoId(),
                    ]
                );
            // caso não exista, inicia o processo de criação da entity
            if (!$data instanceof ObjetoAvaliado) {
                $data = $this
                    ->getResource()
                    ->create($dto, $transactionId, true);

                $this->transactionManager->commit($transactionId);
            }

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
