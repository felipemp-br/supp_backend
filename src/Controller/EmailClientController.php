<?php

declare(strict_types=1);
/**
 * /src/Controller/EmailClientController.php.
 */

namespace SuppCore\AdministrativoBackend\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ContaEmailResource;
use SuppCore\AdministrativoBackend\EmailClient\Attachment;
use SuppCore\AdministrativoBackend\EmailClient\EmailClientServiceInterface;
use SuppCore\AdministrativoBackend\EmailClient\Folder;
use SuppCore\AdministrativoBackend\EmailClient\Message;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\RestMethodHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * Class EmailClientController.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/email_client')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class EmailClientController extends Controller
{
    use RestMethodHelper;

    public function __construct(
        private readonly EmailClientServiceInterface $emailClientService,
        ResponseHandler $responseHandler,
        ContaEmailResource $resource
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/folders',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to fetch Folder entities ',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'entities',
                            description: 'array of entities',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'folder', ref: new Model(type: Folder::class)),
                                ]
                            )
                        ),
                        new OA\Property(property: 'total', description: 'total os entities', type: 'int', example: 10),
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function getFoldersAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->getFolders($this->resource->findOne($id))
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/default_folders',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to fetch Default Folder entities ',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'entities',
                            description: 'array of entities',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'folder', ref: new Model(type: Folder::class)),
                                ]
                            )
                        ),
                        new OA\Property(property: 'total', description: 'total os entities', type: 'int', example: 10),
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function getDefaultFoldersAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->getDefaultFolders($this->resource->findOne($id))
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/inbox_folder',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to returns Inbox Folder entity',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'folder', ref: new Model(type: Folder::class)),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function getInboxFolderAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->getInboxFolder($this->resource->findOne($id))
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/messages',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to fetch Messages entities',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'entities',
                            description: 'array of entities',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'message', ref: new Model(type: Message::class)),
                                ]
                            )
                        ),
                        new OA\Property(property: 'total', description: 'total os entities', type: 'int', example: 10),
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function searchMessagesAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->searchMessages(
                        $this->resource->findOne($id),
                        RequestHandler::getCriteria($request),
                        RequestHandler::getLimit($request),
                        RequestHandler::getOffset($request)
                    )
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/message/{folder}/{message}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to returns Message entity',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'message', ref: new Model(type: Message::class)),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function getMessageAction(
        Request $request,
        int $id,
        int|string $folder,
        int|string $message,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->getMessage(
                        $this->resource->findOne($id),
                        $folder,
                        $message
                    )
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/message/{folder}/{message}/{attachment}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag(name: 'EmailClient')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to returns Attachment entity of message',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'attachment', ref: new Model(type: Attachment::class)),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'code', description: 'Error code', type: 'integer'),
                    new OA\Property(property: 'message', description: 'Error description', type: 'string'),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ]
            )
        )
    )]
    public function getAttachmentAction(
        Request $request,
        int $id,
        int|string $folder,
        int|string $message,
        int|string $attachment,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $this->emailClientService->getAttachment(
                        $this->resource->findOne($id),
                        $folder,
                        $message,
                        $attachment
                    )
                );
        } catch (Throwable $e) {
            throw $this->handleRestMethodException($e, $id);
        }
    }
}
