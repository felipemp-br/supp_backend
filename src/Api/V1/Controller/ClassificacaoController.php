<?php

declare(strict_types=1);
/**
 * /src/Controller/ClassificacaoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Doctrine\ORM\NonUniqueResultException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Domain\Entry;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ClassificacaoResource getResource()
 */
#[Route(path: '/v1/administrativo/classificacao')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Classificacao')]
class ClassificacaoController extends Controller
{
    // Traits
    use Actions\Colaborador\FindOneAction;
    use Actions\Colaborador\FindAction;
    use Actions\Admin\CreateAction;
    use Actions\Admin\UpdateAction;
    use Actions\Admin\PatchAction;
    use Actions\Admin\DeleteAction;
    use Actions\Colaborador\CountAction;

    public function __construct(
        ClassificacaoResource $resource,
        ResponseHandler $responseHandler
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * Endpoint action to criar um direito de acesso sobre uma classificação.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/visibilidade',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PUT']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to create acl roles to entity',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'tipo',
                            description: 'type of role',
                            type: 'string',
                            example: 'usuario|setor'
                        ),
                        new OA\Property(
                            property: 'poderes',
                            description: 'array of permissions',
                            type: 'array',
                            items: new OA\Items(
                                type: 'string',
                                example: 'criar'
                            )
                        ),
                        new OA\Property(
                            property: 'valor',
                            description: 'role identifier',
                            type: 'string',
                            example: 'ROLE_USER|usarname'
                        ),
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
    public function createVisibilidadeAction(
        Request $request,
        int $id,
        UsuarioRepository $usuarioRepository,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PUT'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $classificacao = $this->getResource()->getRepository()->find($id);

        $objectIdentity = ObjectIdentity::fromDomainObject($classificacao);
        $aclObject = $aclProvider->findAcl($objectIdentity);

        try {
            $maskBuilder = new MaskBuilder();

            $poderes = $request->get('poderes');

            foreach ($poderes as $poder) {
                switch ($poder) {
                    case 'master':
                        $maskBuilder->add(MaskBuilder::MASK_MASTER);
                        break;
                    case 'criar':
                        $maskBuilder->add(MaskBuilder::MASK_CREATE);
                        break;
                    case 'editar':
                        $maskBuilder->add(MaskBuilder::MASK_EDIT);
                        break;
                    case 'ver':
                        $maskBuilder->add(MaskBuilder::MASK_VIEW);
                        break;
                    case 'apagar':
                        $maskBuilder->add(MaskBuilder::MASK_DELETE);
                        break;
                }
            }

            $tipo = $request->get('tipo', false);
            $valor = $request->get('valor', false);

            if ('usuario' === $tipo) {
                $usuario = $usuarioRepository->find($valor);
                $securityIdentity = UserSecurityIdentity::fromAccount($usuario);
            } elseif ('setor' === $tipo) {
                $securityIdentity = new RoleSecurityIdentity('ACL_SETOR_'.$valor);
            } elseif ('unidade' === $tipo) {
                $securityIdentity = new RoleSecurityIdentity('ACL_UNIDADE_'.$valor);
            } else {
                $securityIdentity = new RoleSecurityIdentity($valor);
            }

            /**
             * @var Entry $ace
             */
            foreach ($aclObject->getObjectAces() as $index => $ace) {
                // remove eventual existente
                if (($ace->getSecurityIdentity() instanceof RoleSecurityIdentity)
                    && ($securityIdentity instanceof RoleSecurityIdentity)
                    && ($ace->getSecurityIdentity()->getRole() === $securityIdentity->getRole())
                    && 0 !== $ace->getMask()
                ) {
                    $aclObject->deleteObjectAce($index);
                    $aclProvider->updateAcl($aclObject);
                }
                // remove eventual existente
                if (($ace->getSecurityIdentity() instanceof UserSecurityIdentity)
                    && ($securityIdentity instanceof UserSecurityIdentity)
                    && ($ace->getSecurityIdentity()->getUsername() === $securityIdentity->getUsername())
                    && 0 !== $ace->getMask()
                ) {
                    $aclObject->deleteObjectAce($index);
                    $aclProvider->updateAcl($aclObject);
                }
                // remove de todos os usuários
                if (($ace->getSecurityIdentity() instanceof RoleSecurityIdentity)
                    && ('ROLE_USER' === $ace->getSecurityIdentity()->getRole())
                    && in_array($ace->getMask(), [MaskBuilder::MASK_MASTER, MaskBuilder::MASK_OWNER], true)
                ) {
                    $aclObject->deleteObjectAce($index);
                    $aclProvider->updateAcl($aclObject);
                }
            }

            $aclObject->insertObjectAce($securityIdentity, $maskBuilder->get());
            $aclProvider->updateAcl($aclObject);

            // o usuário que restringe sempre deve ser master
            if (!$authorizationChecker->isGranted('MASTER', $classificacao)) {
                $usuario = $tokenStorage->getToken()->getUser();
                $aclObject->insertObjectAce(
                    UserSecurityIdentity::fromAccount($usuario),
                    MaskBuilder::MASK_MASTER
                );
                $aclProvider->updateAcl($aclObject);
            }

            return new JsonResponse(
                [
                    'tipo' => $tipo,
                    'poderes' => $poderes,
                    'valor' => $valor,
                ]
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to remover um direito de acesso sobre uma classificacao.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    #[Route(
        path: '/{classificacaoId}/visibilidade/{visibilidadeId}',
        requirements: [
            'classificacaoId' => '\d+',
            'visibilidadeId' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to removes acl roles from entity',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'id', description: 'Ace id', type: 'integer'),
                ],
                type: 'object',
                example: [
                    'id' => 1,
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
    public function destroyVisibilidadeAction(
        Request $request,
        int $classificacaoId,
        int $visibilidadeId,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $classificacao = $this->getResource()->getRepository()->find($classificacaoId);

        try {
            $objectIdentity = ObjectIdentity::fromDomainObject($classificacao);
            $aclObject = $aclProvider->findAcl($objectIdentity);

            $aceId = null;

            /**
             * @var Entry $ace
             */
            foreach ($aclObject->getObjectAces() as $index => $ace) {
                if (($ace->getId() === $visibilidadeId)
                    && (0 !== $ace->getMask())
                ) {
                    $aceId = $ace->getId();
                    $aclObject->deleteObjectAce($index);
                    $aclProvider->updateAcl($aclObject);
                }
            }

            // se ficar sem nenhuma, tem que recolocar a default
            if ([] === $aclObject->getObjectAces()) {
                $securityIdentity = new RoleSecurityIdentity('ROLE_USER');
                $aclObject->insertObjectAce($securityIdentity, MaskBuilder::MASK_MASTER);
                $aclProvider->updateAcl($aclObject);
            }

            // o usuário que restringe sempre deve ser master
            if (!$authorizationChecker->isGranted('MASTER', $classificacao)) {
                $usuario = $tokenStorage->getToken()->getUser();
                $aclObject->insertObjectAce(
                    UserSecurityIdentity::fromAccount($usuario),
                    MaskBuilder::MASK_MASTER
                );
                $aclProvider->updateAcl($aclObject);
            }

            return new JsonResponse(
                [
                    'id' => $aceId,
                ]
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $classificacaoId);
        }
    }

    /**
     * Endpoint action to get visibilidade status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws NonUniqueResultException
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/visibilidade',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to returns acl roles from classificação',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                description: 'list of permissions',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'label',
                            description: 'label of role type',
                            type: 'string',
                            example: 'TODOS OS USUÁRIOS'
                        ),
                        new OA\Property(
                            property: 'tipo',
                            description: 'type of role',
                            type: 'string',
                            example: 'usuario|setor'
                        ),
                        new OA\Property(
                            property: 'poderes',
                            description: 'array of permissions',
                            type: 'array',
                            items: new OA\Items(
                                type: 'string',
                                example: 'criar'
                            )
                        ),
                        new OA\Property(
                            property: 'valor',
                            description: 'role identifier',
                            type: 'string',
                            example: 'ROLE_USER|usarname'
                        ),
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
    public function getVisibilidadeAction(
        Request $request,
        int $id,
        SetorRepository $setorRepository,
        UsuarioRepository $usuarioRepository,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $classificacao = $this->getResource()->getRepository()->find($id);

        try {
            // Fetch data from database

            $result = [];

            $acl = $aclProvider->findAcl(ObjectIdentity::fromDomainObject($classificacao));

            /**
             * @var Entry $ace
             */
            foreach ($acl->getObjectAces() as $ace) {
                $mask = new MaskBuilder($ace->getMask());
                $pattern = $mask->getPattern();
                $poderes = [];

                if (strpos($pattern, 'M') > 0) {
                    $poderes[] = 'ADMINISTRADOR';
                }

                if (strpos($pattern, 'C') > 0) {
                    $poderes[] = 'CRIAR';
                }

                if (strpos($pattern, 'E') > 0) {
                    $poderes[] = 'EDITAR';
                }

                if (strpos($pattern, 'V') > 0) {
                    $poderes[] = 'VER';
                }

                if (strpos($pattern, 'D') > 0) {
                    $poderes[] = 'APAGAR';
                }

                if (strpos($pattern, 'N') > 0) {
                    $poderes[] = 'ADMINISTRADOR';
                }

                if (0 !== $ace->getMask()) {
                    if ($ace->getSecurityIdentity() instanceof UserSecurityIdentity) {
                        $user = $usuarioRepository->findOneBy(
                            [
                                'username' => $ace->getSecurityIdentity()->getUsername(),
                            ]
                        );
                        $tipo = null !== $user->getColaborador() ? 'usuario' : 'usuario_externo';
                        $result[] = [
                            'id' => $ace->getId(),
                            'label' => $user->getNome().' ('.substr(
                                $ace->getSecurityIdentity()->getUsername(),
                                0,
                                5
                            ).'******)',
                            'tipo' => $tipo,
                            'poderes' => $poderes,
                            'valor' => $ace->getSecurityIdentity()->getUsername(),
                        ];
                    } else {
                        $roles = explode('_', (string) $ace->getSecurityIdentity()->getRole());
                        switch ($roles[1]) {
                            case 'SETOR':
                                $setor = $setorRepository->find((int) $roles[2]);
                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $setor->getNome().' ('.$setor->getUnidade()->getSigla().')',
                                    'tipo' => 'SETOR',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                ];
                                break;
                            case 'UNIDADE':
                                $setor = $setorRepository->find((int) $roles[2]);
                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $setor->getNome().' ('.$setor->getSigla().')',
                                    'tipo' => 'UNIDADE',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                ];
                                break;
                            default:
                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => 'ROLE_USER' === $ace->getSecurityIdentity()->getRole(
                                    ) ? 'TODOS OS USUÁRIOS' : $ace->getSecurityIdentity()->getRole(),
                                    'tipo' => 'PERFIL',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                ];
                                break;
                        }
                    }
                }
            }

            return new JsonResponse($result);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
