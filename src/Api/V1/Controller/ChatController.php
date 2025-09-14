<?php

declare(strict_types=1);
/**
 * /src/Controller/ChatController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatResource as ApiResource;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @method resource getResource()
 */
#[Route(path: '/v1/administrativo/chat')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Chat')]
class ChatController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\User\DeleteAction;
    use Actions\User\CountAction;

    public function __construct(
        ApiResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint shortcut action to create or get chat.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/criar_ou_retornar/{id}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function criarOuRetornarAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this
                ->getResource()
                ->criarOuRetorar($id, $transactionId);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint to action to fin chat list.
     *
     * @throws Throwable
     */
    #[Route(path: '/find_chat_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function findChatListAction(
        Request $request,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $criteria = RequestHandler::getCriteria($request);
            $limit = RequestHandler::getLimit($request);
            $offset = RequestHandler::getOffset($request);
            $data = $this
                ->getResource()
                ->findChatList(null, $criteria, $limit, $offset);

            return $this->getResponseHandler()->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
