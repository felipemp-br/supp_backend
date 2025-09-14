<?php

declare(strict_types=1);
/**
 * /src/Controller/AuthController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Controller;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use InvalidArgumentException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\MicrosecondBasedDateConversion;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder as JWTBuilder;
use Lcobucci\JWT\Token\Parser as JWTParser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidPayloadException;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use LogicException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Entity\UserInterface;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Security\RolesService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\JSON;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Exception\ValidatorException;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function sprintf;

/**
 * Class AuthController.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/auth')]
class AuthController extends Controller
{
    /**
     * Endpoint action to get user Json Web Token (JWT) for authentication.
     *
     * @throws LogicException
     * @throws HttpException
     */
    #[Route(path: '/get_token', methods: ['POST'])]
    #[OA\Post]
    #[OA\RequestBody(
        description: 'Credentials object',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'username' => 'username',
                        'password' => 'password',
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'JSON Web Token for user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'token' => '_json_web_token_',
                ]
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
    #[OA\Tag(name: 'Authentication')]
    public function getTokenAction(): never
    {
        $message = sprintf(
            'You need to send JSON body to obtain token eg. %s',
            JSON::encode(
                [
                    'username' => 'username',
                    'password' => 'password',
                ]
            )
        );

        throw new HttpException(400, $message);
    }

    /**
     * Endpoint action to refresh user Json Web Token (JWT) for authentication.
     *
     * @throws Throwable
     */
    #[Route(path: '/refresh_token', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Security(name: 'Bearer')]
    #[OA\Response(
        response: 200,
        description: 'JSON Web Token for user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'token' => '_json_web_token_',
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
    #[OA\Tag(name: 'Authentication')]
    public function refreshTokenAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        if ($tokenStorage->getToken()?->getUser()) {
            try {
                $user = $tokenStorage->getToken()->getUser();
                $jwt = $jwtManager->create($user);
                $response = new JWTAuthenticationSuccessResponse($jwt);
                $event = new AuthenticationSuccessEvent(
                    [
                        'token' => $jwt,
                    ],
                    $user,
                    $response
                );

                $dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

                $response->setData($event->getData());

                return $response;
            } catch (Throwable $exception) {
                throw $this->handleRestMethodException($exception);
            }
        }
    }

    /**
     * Endpoint action to get payload from Json Web Token (JWT).
     *
     * @throws Throwable
     */
    #[Route(path: '/payload_token', methods: ['GET'])]
    #[Security(name: 'Bearer')]
    #[OA\Response(
        response: 200,
        description: 'JSON payload from Web Token',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'iat', description: 'token creation', type: 'integer'),
                    new OA\Property(property: 'username', description: 'User name', type: 'string'),
                    new OA\Property(
                        property: 'roles',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'role', type: 'string'),
                            ],
                            type: 'object'
                        )
                    ),
                    new OA\Property(property: 'version', description: 'Version API', type: 'string'),
                    new OA\Property(property: 'exp', description: 'Version API', type: 'string'),
                    new OA\Property(property: 'checksum', description: 'Hash of api key', type: 'string'),
                    new OA\Property(property: 'id', description: 'User id', type: 'integer'),
                    new OA\Property(property: 'nome', description: 'User name', type: 'string'),
                    new OA\Property(property: 'email', description: 'User email', type: 'string'),
                ],
                type: 'object',
                example: [
                    'iat' => 1580837366,
                    'roles' => '',
                    'username' => '',
                    'ip' => '',
                    'version' => '',
                    'exp' => 1580823797,
                    'checksum' => '',
                    'id' => 0,
                    'nome' => '',
                    'email' => '',
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
    #[OA\Tag(name: 'Authentication')]
    public function payloadTokenAction(
        Request $request,
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $params = $request->headers->all()['authorization'][0];
            $token = explode(' ', $params)[1];
            $jws = (new JWTParser(new JoseEncoder()))->parse($token);
            $jsonResponse = json_encode($jws->claims()->all());
            $response = new Response();
            $response->setContent($jsonResponse);

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint to get an e-mail to recover password.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/recover_password', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Credentials object',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'username',
                            description: 'Username CPF/CNPJ',
                            type: 'string'
                        ),
                        new OA\Property(
                            property: 'email',
                            description: 'Username E-Mail',
                            type: 'string'
                        ),
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(response: 200, description: 'Success Response')]
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
    #[OA\Tag(name: 'Authentication')]
    public function recoverPasswordAction(
        Request $request,
        ParameterBagInterface $parameterBag,
        UsuarioResource $usuarioResource,
        Environment $twig,
        MailerInterface $mailer,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        $this->validateLoginType('login_interno_ativo', $parameterBag);

        $response = new Response();

        if (!$request->get('username') || !$request->get('email')) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent('Invalid JSON');

            return $response;
        }
        $usuario = $usuarioResource->findOneBy(
            [
                'username' => $request->get('username'),
                'email' => $request->get('email'),
            ]
        );
        if (!$usuario instanceof \SuppCore\AdministrativoBackend\Entity\Usuario) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setContent('User Not found');

            return $response;
        }

        $dateTime = new DateTime();
        $token = hash(
            'SHA256',
            (string) ($usuario->getUsername().'_'.
                $usuario->getEmail().'_'.
                $dateTime->format('Ymd').'_'.
                $parameterBag->get('token_auth'))
        );

        $url = $parameterBag->get('supp_core.administrativo_backend.url_sistema_backend').
            '/auth/recover_password?username='.$usuario->getUsername().
            '&email='.$usuario->getEmail().
            '&reset_token='.$token;

        $message = (new Email())
            ->subject(
                'Recuperação de senha no '.$parameterBag
                    ->get('supp_core.administrativo_backend.nome_sistema')
            )
            ->from($parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->to($usuario->getEmail())
            ->html(
                $twig->render(
                    $parameterBag->get('supp_core.administrativo_backend.template_email_recupera_senha'),
                    [
                        'sistema' => $parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$parameterBag->get('kernel.environment')],
                        'urlreset' => $url,
                    ]
                )
            );

        $mailer->send($message);

        return $response;
    }

    /**
     * Endpoint to validate token and reset password.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    #[Route(path: '/recover_password', methods: ['GET'])]
    #[OA\Parameter(
        name: 'username',
        description: 'Username CNPJ/CPF',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'Username E-Mail',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'reset_token',
        description: 'reset_token',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(response: 200, description: 'Success Response')]
    #[OA\Tag(name: 'Authentication')]
    public function recoverPasswordResetAction(
        Request $request,
        ParameterBagInterface $parameterBag,
        UsuarioResource $usuarioResource,
        TransactionManager $transactionManager,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        RolesService $rolesService,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods = ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);
        $this->validateLoginType('login_interno_ativo', $parameterBag);

        $response = new Response();

        if (!$request->get('username')
            || !$request->get('email')
            || !$request->get('reset_token')
        ) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent('Invalid Query Params');

            return $response;
        }
        $usuario = $usuarioResource->findOneBy(
            [
                'username' => $request->get('username'),
                'email' => $request->get('email'),
            ]
        );
        if (!$usuario instanceof \SuppCore\AdministrativoBackend\Entity\Usuario) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setContent('User Not found');

            return $response;
        }

        $dateTime = new DateTime();
        $realToken = hash(
            'SHA256',
            (string) ($usuario->getUsername().'_'.
                $usuario->getEmail().'_'.
                $dateTime->format('Ymd').'_'.
                $parameterBag->get('token_auth'))
        );

        if ($request->get('reset_token') !== $realToken) {
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $response->setContent('Invalid Token');

            return $response;
        }

        if (!$authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $token = new UsernamePasswordToken(
                $usuario,
                $usuario->getPassword(),
                $rolesService->getContextualRoles($usuario)
            );
            $tokenStorage->setToken($token);
        }

        $dto = $usuarioResource->getDtoForEntity($usuario->getId(), Usuario::class);

        $transactionId = $transactionManager->begin();
        $usuarioResource->resetaSenha($usuario->getId(), $dto, $transactionId, true);
        $transactionManager->commit($transactionId);

        $response->setContent('Password resetado e enviado para o e-mail com sucesso!.');

        return $response;
    }

    /**
     * Endpoint action to get user Json Web Token (JWT) for authentication with x509 cert.
     *
     * @throws Throwable
     */
    #[Route(path: '/x509_get_token', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'JSON Web Token for user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'token' => '_json_web_token_',
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
    #[OA\Tag(name: 'Authentication')]
    public function getTokenX509Action(
        Request $request,
        TokenStorageInterface $tokenStorage,
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        AuthorizationCheckerInterface $authorizationChecker,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            if (!$tokenStorage->getToken()
                || !$authorizationChecker->isGranted('ROLE_X509')
            ) {
                throw new BadCredentialsException('Erro no login por certificado digital!');
            }

            $user = $tokenStorage->getToken()->getUser();

            $jwt = $jwtManager->create($user);
            $agora = new DateTime();
            $exp = clone $agora;
            $exp->add(
                new DateInterval(
                    'PT'.$this->parameterBag->get('supp_core.administrativo_backend.jwt_exp').'S'
                )
            );

            return new RedirectResponse(
                $this->parameterBag->get(
                    'supp_core.administrativo_backend.url_sistema_frontend'
                ).'/auth/login?token='.$jwt.'&exp='.$exp->getTimestamp().'&timestamp='.$agora->getTimestamp()
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to authenticate user from GovBr.
     *
     * @throws Throwable
     */
    #[Route(path: '/govbr_get_token', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Credentials object',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'code' => 'code',
                        'redirectUri' => 'redirectUri'
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'JSON Web Token for user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'token' => '_json_web_token_',
                ]
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
    #[OA\Tag(name: 'Authentication')]
    public function ssoGovBrAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        ParameterBagInterface $parameterBag,
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $user = $tokenStorage->getToken()->getUser();
            $jwt = $jwtManager->create($user);
            $response = new JWTAuthenticationSuccessResponse($jwt);
            $event = new AuthenticationSuccessEvent(
                [
                    'token' => $jwt,
                ],
                $user,
                $response
            );

            $dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);
            $response->setData($event->getData());

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to authenticate user from LDAP.
     *
     * @throws Throwable
     */
    #[Route(path: '/ldap_get_token', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Credentials object',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'username' => 'username',
                        'password' => 'password',
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'JSON Web Token for user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'token' => '_json_web_token_',
                ]
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
    #[OA\Tag(name: 'Authentication')]
    public function getTokenLdapAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['POST'];
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $user = $tokenStorage->getToken()->getUser();
            $jwt = $jwtManager->create($user);
            $response = new JWTAuthenticationSuccessResponse($jwt);
            $event = new AuthenticationSuccessEvent(
                [
                    'token' => $jwt,
                ],
                $user,
                $response
            );

            $dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);
            $response->setData($event->getData());

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to get user's signer Json Web Token (JWT) for authentication.
     *
     * @throws LogicException
     * @throws HttpException|Throwable
     */
    #[Route(path: '/assinador_get_token', methods: ['POST'])]
    #[OA\Post]
    #[OA\RequestBody(
        description: 'Credentials object',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'username' => 'username',
                        'signature' => 'signature',
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "JSON Web Token for user's signer",
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', description: 'Json Web Token', type: 'string'),
                ],
                type: 'object',
                example: [
                    'jwt' => '_json_web_token_',
                ]
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
    #[OA\Tag(name: 'Authentication')]
    public function getAssinadorTokenAction(
        Request $request,
        ParameterBagInterface $parameterBag,
        UsuarioResource $usuarioResource,
        LoggerInterface $logger
    ): Response {
        try {
            $datetime = new DateTime();
            $username = $request->get('username');
            $assinatura = base64_decode((string) $request->get('signature'), true);
            $hash = hash('sha256', $username.'_'.$datetime->format('Ymd'));

            $signerProxyParams = [];
            $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

            if ($signerProxy) {
                $signerProxyParams = explode(' ', $signerProxy);
            }
            $filename = '/tmp/'.$hash.'.p7s';
            file_put_contents($filename, $assinatura);
            $params = [
                'java',
                '-jar',
                '/usr/local/bin/supp-signer.jar',
                '--mode=verify',
                '--hash='.$hash,
            ];
            $process = new Process(
                array_merge($params, $signerProxyParams)
            );
            $process->run();
            unlink($filename);

            // executes after the command finishes
            $valid = $process->isSuccessful();

            $result = $process->getOutput();

            if (!$valid) {
                throw new BadRequestHttpException('Dados não conferem!', null, 401);
            }

            $usernames = [];

            preg_match_all(
                '/CPF:\d{3}\.?\d{3}\.?\d{3}\.?\-?\d{2}:|CNPJ:\d{2}\.?\d{3}\.?\d{3}\/?\d{4}\-?\d{2}:/',
                $result,
                $usernames
            );

            if (!isset($usernames[0][0])) {
                throw new BadRequestHttpException('Dados não conferem!', null, 401);
            }

            $username = preg_replace('/\D/', '', (string) $usernames[0][0]);

            $usuario = $usuarioResource->findOneBy(
                [
                    'username' => $username,
                ]
            );

            if (!$usuario || !$usuario->getEnabled()) {
                throw new BadRequestHttpException('Dados não conferem!', null, 401);
            }

            $mercureSecret = $parameterBag->get('mercure_jwt_secret');

            $token = (new JWTBuilder(new JoseEncoder(), new MicrosecondBasedDateConversion()))
                ->withClaim(
                    'mercure',
                    [
                        'subscribe' => [
                            $username,
                            '/{versao}/{modulo}/{resource}/{id}',
                            '/assinador/'.$username,
                        ],
                        'publish' => [
                            $username,
                            '/assinador/'.$username,
                        ],
                    ]
                )
                ->withClaim('trusted', 'signer')
                ->getToken(new Sha256(), InMemory::plainText($mercureSecret));

            $response = [
                'jwt' => $token->toString(),
            ];

            return new JsonResponse(
                $response
            );
        } catch (Throwable $exception) {
            $logger->critical($exception->getMessage().' - '.$exception->getTraceAsString());

            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint to alter password.
     *
     * @throws Throwable
     */
    #[Route(path: '/update_password', methods: ['POST'])]
    #[OA\Post]
    #[OA\RequestBody(
        description: 'content',
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    example: [
                        'old_password' => 'old_password',
                        'new_password' => 'new_password',
                        'token' => 'token',
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(response: 200, description: 'Success Response')]
    #[OA\Tag(name: 'Authentication')]
    public function alterarSenhaAction(
        Request $request,
        UsuarioResource $usuarioResource,
        TokenStorageInterface $tokenStorage,
        RolesService $rolesService,
        ResponseHandler $responseHandler,
        JWTTokenManagerInterface $jwtManager
    ): Response {
        try {
            $id = null;

            if (!$request->get('old_password')
                || !$request->get('new_password')
                || !$request->get('token')
            ) {
                throw new InvalidArgumentException('Invalid Query Params');
            }

            $request->attributes->set('ignore_expired_password', true);

            // Mechanism for jwt token validation
            $payload = $jwtManager->parse($request->get('token'));
            $idClaim = $jwtManager->getUserIdClaim();

            if (!isset($payload[$idClaim])) {
                throw new InvalidPayloadException($idClaim);
            }

            if ('login' !== $payload['authProviderKey']) {
                throw new ValidatorException('Alteração de senha disponível apenas para autenticação com usuário e senha.');
            }

            $usuario = $usuarioResource->getRepository()->findUserByUsernameOrEmail($payload[$idClaim]);

            if (!$usuario instanceof UserInterface) {
                $tokenStorage->setToken(null);

                throw new NotFoundHttpException('User not found');
            }

            $token = new UsernamePasswordToken(
                $usuario,
                $payload['authProviderKey'],
                $rolesService->getContextualRoles($usuario)
            );

            $tokenStorage->setToken($token);

            $id = $usuario->getId();
            $transactionId = $this->transactionManager->begin();
            $responseHandler->setResource($usuarioResource);
            /** @var Usuario $dto */
            $dto = $usuarioResource->getDtoForEntity($usuario->getId(), Usuario::class);

            $dto->setPlainPassword($request->get('new_password'));
            $dto->setCurrentPlainPassword($request->get('old_password'));

            $response = $responseHandler
                ->createResponse($request, $usuarioResource->alterarSenha($id, $dto, $transactionId));

            $this->transactionManager->commit($transactionId);

            return $response;
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    private function validateLoginType(string $loginType, ParameterBagInterface $parameterBag)
    {
        if (!$parameterBag->get('supp_core.administrativo_backend.'.$loginType)) {
            throw new Exception("Tipo de Login '{$loginType}' desativado");
        }
    }
}
