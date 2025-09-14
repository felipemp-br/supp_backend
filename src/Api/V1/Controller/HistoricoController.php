<?php

declare(strict_types=1);
/**
 * /src/Controller/HistoricoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use LogicException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method HistoricoResource getResource()
 */
#[Route(path: '/v1/administrativo/historico')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Historico')]
class HistoricoController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Root\CreateAction;
    use Actions\Root\UpdateAction;
    use Actions\Root\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        HistoricoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(path: '', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function findAction(
        Request $request,
        TokenStorageInterface $tokenStorage
    ): Response {
        $allowedHttpMethods = ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $search = RequestHandler::getSearchTerms($request);
        $populate = RequestHandler::getPopulate($request, $this->getResource());

        try {
            $criteria = RequestHandler::getCriteria($request);

            /** @var TokenInterface $tokenInterface */
            $tokenInterface = $tokenStorage->getToken();

            $usuario = $tokenInterface->getUser();

            $criteria['criadoPor.id'] = 'eq:'.$usuario->getId();

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->getResource()->find($criteria, $orderBy, $limit, $offset, $search, $populate)
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

        /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/processo/{id}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function findProcessoAction(
        Request $request,
        int $id,
        AuthorizationCheckerInterface $authorizationChecker,
        ProcessoRepository$processoRepository,
    ): Response {
        $allowedHttpMethods = ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $processo = $processoRepository->find($id);
        if (false === $authorizationChecker->isGranted('VIEW', $processo)) {
            throw new AccessDeniedException('Você não tem permissão para acessar o histórido deste processo.');
        }

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $search = RequestHandler::getSearchTerms($request);
        $populate = RequestHandler::getPopulate($request, $this->getResource());

        try {
            $criteria = RequestHandler::getCriteria($request);

            $criteria['processo.id'] = 'eq:'.$id;

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->getResource()->find($criteria, $orderBy, $limit, $offset, $search, $populate)
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
