<?php

declare(strict_types=1);
/**
 * /src/Controller/DocumentoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use LogicException;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Domain\Entry;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method DocumentoResource getResource()
 */
#[Route(path: '/v1/administrativo/documento')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Documento')]
class DocumentoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\Colaborador\CreateAction;
    use Actions\Colaborador\UpdateAction;
    use Actions\Colaborador\PatchAction;
    use Actions\Colaborador\DeleteAction;
    use Actions\Colaborador\CountAction;
    use Actions\Colaborador\UndeleteAction;

    public function __construct(
        DocumentoResource $resource,
        ResponseHandler $responseHandler,
        private readonly VinculacaoDocumentoRepository $vinculacaoDocumentoRepository,
    ) {
        $this->init($resource, $responseHandler);
    }

    /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/delete_assinatura',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function deleteAssinaturaAction(Request $request, int $id): Response
    {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            // Fetch data from database
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this->getResource()->deleteAssinatura($id, $transactionId);

            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to get visibilidade status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/visibilidade',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function getVisibilidadeAction(
        Request $request,
        int $id,
        SetorRepository $setorRepository,
        UsuarioRepository $usuarioRepository,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        HistoricoResource $historicoResource,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        /** @var Documento $documento */
        $documento = $this->getResource()->getRepository()->find($id);
        $authorized = $authorizationChecker->isGranted('VIEW', $documento);
        if (false === $authorized) {
            // throw new AccessDeniedException(); -- Removido para permitir usuário ver a lista de acesso
            try {
                // Cria histórico de solicitação.
                $transactionId = $this->transactionManager->begin();
                $historicoDto = new HistoricoDTO();
                $historicoDto->setProcesso($documento->getJuntadaAtual()->getVolume()->getProcesso());
                $historicoDto->setDescricao("SOLICITAÇÃO DE LISTA DE ACESSO AO DOCUMENTO $id");
                $historicoResource->create($historicoDto, $transactionId);
                $this->transactionManager->commit($transactionId);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        try {
            // Fetch data from database

            $result = [];

            $acl = $aclProvider->findAcl(ObjectIdentity::fromDomainObject($documento));

            /**
             * @var Entry $ace
             */
            foreach ($acl->getObjectAces() as $ace) {
                $mask = new MaskBuilder($ace->getMask());
                $pattern = $mask->getPattern();
                $poderes = [];

                if (strpos($pattern, 'M') > 0) {
                    $poderes[] = 'MASTER';
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
                    $poderes[] = 'MASTER';
                }

                if (0 !== $ace->getMask()) {
                    if ($ace->getSecurityIdentity() instanceof UserSecurityIdentity) {
                        $user = $usuarioRepository->findOneBy(
                            [
                                'username' => $ace->getSecurityIdentity()->getUsername(),
                            ]
                        );
                        $tipo = null !== $user->getColaborador() ? 'usuario' : 'usuario_externo';
                        $label = $user->getNome().' ('.substr(
                            $ace->getSecurityIdentity()->getUsername(),
                            0,
                            5
                        ).'******)';
                        if (!$authorized) {
                            $label = $user->getNome().' ('.$user->getEmail().')';
                        }
                        $result[] = [
                            'id' => $ace->getId(),
                            'label' => $label,
                            'tipo' => $tipo,
                            'poderes' => $poderes,
                            'valor' => $ace->getSecurityIdentity()->getUsername(),
                            'hasEmail' => !!$user->getEmail(),
                        ];
                    } else {
                        $roles = explode('_', (string) $ace->getSecurityIdentity()->getRole());
                        switch ($roles[1]) {
                            case 'SETOR':
                                $setor = $setorRepository->find((int) $roles[2]);
                                $label = $setor->getNome().' ('.$setor->getUnidade()->getSigla().')';
                                if(!$authorized) {
                                    $label .= $setor->getEmail()? ' - ('.$setor->getEmail().')' : '';
                                }
                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $label,
                                    'tipo' => 'SETOR',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                    'hasEmail' => !!$setor->getEmail(),
                                ];
                                break;
                            case 'UNIDADE':
                                $setor = $setorRepository->find((int) $roles[2]);
                                $label = $setor->getNome().' ('.$setor->getSigla().')';
                                if(!$authorized) {
                                    $label .= $setor->getEmail()? ' - ('.$setor->getEmail().')' : '';
                                }
                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $label,
                                    'tipo' => 'UNIDADE',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                    'hasEmail' => !!$setor->getEmail(),
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
                                    'hasEmail' => false
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

    /**
     * Endpoint action to criar ou remover uma visibilidade sobre um documento.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/visibilidade',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PUT']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function createVisibilidadeAction(
        Request $request,
        int $id,
        UsuarioRepository $usuarioRepository,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['PUT'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $documento = $this->getResource()->getRepository()->find($id);

        if (!$authorizationChecker->isGranted('MASTER', $documento)) {
            throw new AccessDeniedException();
        }

        $objectIdentity = ObjectIdentity::fromDomainObject($documento);
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
            if (!$authorizationChecker->isGranted('MASTER', $documento)) {
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
     * Endpoint action to criar ou remover uma visibilidade sobre um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws Throwable
     */
    #[Route(
        path: '/{documentoId}/visibilidade/{visibilidadeId}',
        requirements: [
            'documentoId' => '\d+',
            'visibilidadeId' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function destroyVisibilidadeAction(
        Request $request,
        int $documentoId,
        int $visibilidadeId,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $documento = $this->getResource()->getRepository()->find($documentoId);

        if (!$authorizationChecker->isGranted('MASTER', $documento)) {
            throw new AccessDeniedException();
        }

        try {
            $objectIdentity = ObjectIdentity::fromDomainObject($documento);
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
                $aclObject->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($aclObject);
            }

            // o usuário que restringe sempre deve ser master
            if (!$authorizationChecker->isGranted('MASTER', $documento)) {
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
            throw $this->handleRestMethodException($exception, $documentoId);
        }
    }

    /**
     * Endpoint action to convert document to pdf.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/convertToPdf/{id}',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function convertToPdfAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            // To use and save on the database
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
                ->convertToPDF($id, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_OK);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action para remover as visibilidades de um documento.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws Throwable
     */
    #[Route(
        path: '/deletevisibilidade/{documentoId}',
        requirements: [
            'documentoId' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function destroyDocumentoVisibilidadeAction(
        Request $request,
        int $documentoId,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $documento = $this->getResource()->getRepository()->find($documentoId);

        if (!$documento instanceof Documento) {
            throw new Exception('Documento não encontrado');
        }

        if ($documento && !$authorizationChecker->isGranted('MASTER', $documento)) {
            throw new Exception('Usuário não possui permissão para exclusão da restrição');
        }

        try {
            $objectIdentity = ObjectIdentity::fromDomainObject($documento);
            $aclObject = $aclProvider->findAcl($objectIdentity);

            $aceId = null;

            /**
             * @var Entry $ace
             */
            foreach ($aclObject->getObjectAces() as $ace) {
                if (0 !== $ace->getMask()) {
                    $aceId = $ace->getId();
                    $aclObject->deleteObjectAce(0);
                    $aclProvider->updateAcl($aclObject);
                }
            }

            // se ficar sem nenhuma, tem que recolocar a default
            if ([] === $aclObject->getObjectAces()) {
                $securityIdentity = new RoleSecurityIdentity('ROLE_USER');
                $aclObject->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($aclObject);
            }

            // o usuário que restringe sempre deve ser master
            if (!$authorizationChecker->isGranted('MASTER', $documento)) {
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
            throw $this->handleRestMethodException($exception, $documentoId);
        }
    }

    /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{documentoOrigemId}/converte_minuta_anexo/{documentoDestinoId}',
        requirements: [
            'documentoOrigemId' => '\d+',
            'documentoDestinoId' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function converteMinutaEmAnexoAction(
        Request $request,
        int $documentoOrigemId,
        int $documentoDestinoId,
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            // Fetch data from database
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $this->getResource()
                ->converteMinutaEmAnexo($documentoOrigemId, $documentoDestinoId, $transactionId);

            $this->transactionManager->commit($transactionId);

            $documentoDestino = $this->getResource()->getRepository()->find($documentoDestinoId);

            // FORÇANDO A HIDRATAÇÃO DAS VINCULACOES
            $vinculacao = $this->vinculacaoDocumentoRepository->findBy([
                'documento' => $documentoDestinoId,
            ]);

            foreach ($vinculacao as $vinc) {
                $documentoDestino->addVinculacaoDocumento($vinc);
            }

            return $this
                ->getResponseHandler()
                ->createResponse($request, $documentoDestino);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $documentoOrigemId);
        }
    }

    /**
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/converte_anexo_minuta/{tarefaId}',
        requirements: [
            'id' => '\d+',
            'tarefaId' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function converteAnexoEmMinutaAction(
        Request $request,
        int $id,
        int $tarefaId,
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            // Fetch data from database
            $transactionId = $this->transactionManager->begin();

            $context = RequestHandler::getContext($request);

            foreach ($context as $name => $value) {
                $this->transactionManager->addContext(
                    new Context($name, $value),
                    $transactionId
                );
            }

            $data = $this->getResource()
                ->converteAnexoEmMinuta($id, $tarefaId, $transactionId);

            $this->transactionManager->commit($transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Download em PDF da minuta e os anexos
     *
     * @throws Throwable
     */
    #[Route(
        path: '/download/{id}/minuta_anexos/pdf',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_USER')]
    public function downloadMinutaAnexosAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null,
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $transactionId = $this->transactionManager->begin();

            $data = $this->getResource()->downloadAllPdf($id, $transactionId);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, Response::HTTP_OK);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws Throwable
     * @throws SyntaxError
     */
    #[Route(
        path: '/{id}/sendEmail',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['POST']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function sendEmailAction(Request $request, int $id): Response
    {
        $transactionId = $this->transactionManager->begin();

        $context = RequestHandler::getContext($request);

        foreach ($context as $name => $value) {
            $this->transactionManager->addContext(
                new Context($name, $value),
                $transactionId
            );
        }

        $entity = $this->getResource()->sendEmailMethod($request, $id, $transactionId);

        $this->transactionManager->commit($transactionId);

        return $this->getResponseHandler()->createResponse($request, $entity);
    }
}
