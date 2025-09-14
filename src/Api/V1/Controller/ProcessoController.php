<?php

declare(strict_types=1);
/**
 * /src/Controller/ProcessoController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use LogicException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Elastic\ElasticQueryBuilderService;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use SuppCore\AdministrativoBackend\Rest\ResponseHandler;
use SuppCore\AdministrativoBackend\Rest\Traits\Actions;
use SuppCore\AdministrativoBackend\Timeline\TimelineEvent;
use SuppCore\AdministrativoBackend\Timeline\TimelineProcessoService;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
use Twig\Environment;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FundamentacaoRestricaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFundamentacaoResource;
use SuppCore\AdministrativoBackend\Entity\FundamentacaoRestricao;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function strpos;
use function substr;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @method ProcessoResource getResource()
 */
#[Route(path: '/v1/administrativo/processo')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'Processo')]
class ProcessoController extends Controller
{
    // Traits
    use Actions\User\FindOneAction;
    use Actions\User\FindAction;
    use Actions\User\CreateAction;
    use Actions\User\UpdateAction;
    use Actions\User\PatchAction;
    use Actions\Root\DeleteAction;
    use Actions\Colaborador\CountAction;

    /**
     * @param ProcessoResource $resource
     * @param ResponseHandler $responseHandler
     * @param IndexService $processoIndex
     * @param ElasticQueryBuilderService $elasticQueryBuilderService
     * @param TimelineProcessoService $timelineProcessoService
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        ProcessoResource $resource,
        ResponseHandler $responseHandler,
        private readonly IndexService $processoIndex,
        private readonly ElasticQueryBuilderService $elasticQueryBuilderService,
        private readonly TimelineProcessoService $timelineProcessoService,
        private PaginatorInterface $paginator
    ) {
        $this->init($resource, $responseHandler);
        $this->paginator = $paginator;
    }

    /**
     * Endpoint action to get visibilidade status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
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

        $processo = $this->getResource()->getRepository()->find($id);

        $authorized = $authorizationChecker->isGranted('VIEW', $processo);
        if (false === $authorized) {
            // throw new AccessDeniedException(); -- Removido para permitir usuário ver a lista de acesso
            try {
                // Cria histórico de solicitação.
                $transactionId = $this->transactionManager->begin();
                $historicoDto = new HistoricoDTO();
                $historicoDto->setProcesso($processo);
                $historicoDto->setDescricao('SOLICITAÇÃO DE LISTA DE ACESSO');
                $historicoResource->create($historicoDto, $transactionId);
                $this->transactionManager->commit($transactionId);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        try {
            // Fetch data from database

            $result = [];

            $acl = $aclProvider->findAcl(ObjectIdentity::fromDomainObject($processo));

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
                        $nomeUsuario = $user->getNome();
                        $nomeComplemento = substr($ace->getSecurityIdentity()->getUsername(), 0, 5) . '******';
                        $label = $nomeUsuario . ' (' . $nomeComplemento . ')';
                        $fundamentacao = null;
                        $modalidadeFundamentacao = null;
                        $criadoPor = null;
                        if ($authorized) {
                            $fundamentacaoRestricao = $processo
                                ->getFundamentacoesRestricao()
                                ->findFirst(fn(int $key, FundamentacaoRestricao $fr) => $fr->getUsuario() &&
                                    $fr->getUsuario()->getId() === $user->getId());
                            $fundamentacao = $fundamentacaoRestricao?->getFundamentacao();
                            $modalidadeFundamentacao = $fundamentacaoRestricao?->getModalidadeFundamentacao() ? [
                                'id' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getId(),
                                'valor' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getValor()
                            ] : null;
                            $criadoPor = $fundamentacaoRestricao?->getCriadoPor()?->getNome();
                        } else {
                            $label = $user->getNome().' ('.$user->getEmail().')';
                        }

                        $result[] = [
                            'id' => $ace->getId(),
                            'label' => $label,
                            'tipo' => $tipo,
                            'poderes' => $poderes,
                            'valor' => $ace->getSecurityIdentity()->getUsername(),
                            'hasEmail' => !!$user->getEmail(),
                            'fundamentacao' => $fundamentacao,
                            'modalidadeFundamentacao' => $modalidadeFundamentacao,
                            'criadoPor' => $criadoPor
                        ];
                    } else {
                        $roles = explode('_', (string) $ace->getSecurityIdentity()->getRole());
                        switch ($roles[1]) {
                            case 'SETOR':
                                $setor = $setorRepository->find((int) $roles[2]);

                                $label = $setor->getNome().' ('.$setor->getUnidade()->getSigla().')';
                                $fundamentacao = null;
                                $modalidadeFundamentacao = null;
                                $criadoPor = null;
                                if ($authorized) {
                                    $fundamentacaoRestricao = $processo
                                        ->getFundamentacoesRestricao()
                                        ->findFirst(fn(int $key, FundamentacaoRestricao $fr) => $fr->getSetor() &&
                                            $fr->getSetor()->getId() === $setor->getId());
                                    $fundamentacao = $fundamentacaoRestricao?->getFundamentacao();
                                    $modalidadeFundamentacao = $fundamentacaoRestricao
                                        ?->getModalidadeFundamentacao() ? [
                                        'id' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getId(),
                                        'valor' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getValor()
                                    ] : null;
                                    $criadoPor = $fundamentacaoRestricao?->getCriadoPor()?->getNome();
                                } else {
                                    $label .= $setor->getEmail()? ' - ('.$setor->getEmail().')' : '';
                                }

                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $label,
                                    'tipo' => 'SETOR',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                    'hasEmail' => !!$setor->getEmail(),
                                    'fundamentacao' => $fundamentacao,
                                    'modalidadeFundamentacao' => $modalidadeFundamentacao,
                                    'criadoPor' => $criadoPor
                                ];
                                break;
                            case 'UNIDADE':
                                $unidade = $setorRepository->find((int) $roles[2]);

                                $label = $unidade->getNome().' ('.$unidade->getSigla().')';
                                $fundamentacao = null;
                                $modalidadeFundamentacao = null;
                                $criadoPor = null;
                                if ($authorized) {
                                    $fundamentacaoRestricao = $processo
                                        ->getFundamentacoesRestricao()
                                        ->findFirst(fn(int $key, FundamentacaoRestricao $fr) => $fr->getSetor() &&
                                            $fr->getSetor()->getId() === $unidade->getId());
                                    $fundamentacao = $fundamentacaoRestricao?->getFundamentacao();
                                    $modalidadeFundamentacao = $fundamentacaoRestricao
                                        ?->getModalidadeFundamentacao() ? [
                                        'id' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getId(),
                                        'valor' => $fundamentacaoRestricao->getModalidadeFundamentacao()->getValor()
                                    ] : null;
                                    $criadoPor = $fundamentacaoRestricao?->getCriadoPor()?->getNome();
                                } else {
                                    $label .= $unidade->getEmail()? ' - ('.$unidade->getEmail().')' : '';
                                }

                                $result[] = [
                                    'id' => $ace->getId(),
                                    'label' => $label,
                                    'tipo' => 'UNIDADE',
                                    'poderes' => $poderes,
                                    'valor' => $ace->getSecurityIdentity()->getRole(),
                                    'hasEmail' => !!$unidade->getEmail(),
                                    'fundamentacao' => $fundamentacao,
                                    'modalidadeFundamentacao' => $modalidadeFundamentacao,
                                    'criadoPor' => $criadoPor
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
     * Endpoint action to get visibilidade status.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/juntada_index',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function getJuntadaIndexAction(
        Request $request,
        int $id,
        JuntadaRepository $juntadaRepository,
        ComponenteDigitalRepository $componenteDigitalRepository,
        AuthorizationCheckerInterface $authorizationChecker,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $processo = $this->getResource()->getRepository()->find($id);

        if (!$authorizationChecker->isGranted('VIEW', $processo)) {
            throw new AccessDeniedException();
        }

        try {
            // Fetch data from database

            $index = [];
            $index['status'] = 'sem_juntadas';
            $juntada = $juntadaRepository->findLastNaoVinculadaByProcessoId($processo->getId());
            if ($juntada) {
                $index['status'] = 'sucesso';
                $index['juntadaId'] = $juntada->getId();
                $index['numeracaoSequencial'] = $juntada->getNumeracaoSequencial();
                if (!$juntada->getAtivo()) {
                    $index['status'] = 'desentranhada';
                }
                $componenteDigital = $componenteDigitalRepository->findFirstByJuntadaIdAndProcessoId(
                    $juntada->getId()
                );
                if ($componenteDigital) {
                    $index['componenteDigitalId'] = $componenteDigital->getId();
                } else {
                    $index['status'] = 'sem_componentes_digitais';
                    /* @var VinculacaoDocumento $vinculacoesDocumentos */
                    foreach ($juntada->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                        /* @var \SuppCore\AdministrativoBackend\Entity\ComponenteDigital $componenteDigital */
                        foreach ($vinculacaoDocumento->getDocumentoVinculado()
                            ->getComponentesDigitais() as $componenteDigitalVinculado) {
                            $index['componenteDigitalId'] = $componenteDigitalVinculado->getId();
                            $index['status'] = 'sucesso';
                            break 2;
                        }
                    }
                }
            }

            return new JsonResponse($index);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to criar um direito de acesso sobre um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
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
        FundamentacaoRestricaoResource $fundamentacaoRestricaoResource,
        ModalidadeFundamentacaoResource $modalidadeFundamentacaoResource,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ProcessoResource $processoResource,
        SetorRepository $setorRepository,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PUT'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $processo = $this->getResource()->getRepository()->find($id);

        if (!$authorizationChecker->isGranted('MASTER', $processo)) {
            throw new AccessDeniedException();
        }

        $objectIdentity = ObjectIdentity::fromDomainObject($processo);
        $aclObject = $aclProvider->findAcl($objectIdentity);

        try {
            $transactionId = $this->transactionManager->begin();

            $modalidadeFundamentacao = null;
            $arrModalidadeFundamentacao = $request->get('modalidadeFundamentacao');
            if ($arrModalidadeFundamentacao && isset($arrModalidadeFundamentacao['id'])) {
                $modalidadeFundamentacao = $modalidadeFundamentacaoResource->findOne($arrModalidadeFundamentacao['id']);
            }
            if (!$modalidadeFundamentacao) {
                $firstFundamentacaoRestricao = $processo->getFundamentacoesRestricao()->findFirst(
                    fn(int $key, FundamentacaoRestricao $fr) => $fr->getModalidadeFundamentacao()
                );
                if (!$firstFundamentacaoRestricao) {
                    throw new NotFoundHttpException('Modalidade Fundamentação não encontrada');
                }
                $modalidadeFundamentacao = $firstFundamentacaoRestricao->getModalidadeFundamentacao();
            }
            
            $fundamentacaoRestricao = new FundamentacaoRestricao();
            $fundamentacaoRestricao->setProcesso($processo);
            $fundamentacaoRestricao->setModalidadeFundamentacao($modalidadeFundamentacao);
            $fundamentacaoRestricao->setFundamentacao($request->get('fundamentacao'));

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
                $fundamentacaoRestricao->setUsuario($usuario);
            } elseif ('setor' === $tipo) {
                $setor = $setorRepository->find($valor);
                $securityIdentity = new RoleSecurityIdentity('ACL_SETOR_'.$valor);
                $fundamentacaoRestricao->setSetor($setor);
            } elseif ('unidade' === $tipo) {
                $unidade = $setorRepository->find($valor);
                $securityIdentity = new RoleSecurityIdentity('ACL_UNIDADE_'.$valor);
                $fundamentacaoRestricao->setUnidade($unidade);
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
            if (!$authorizationChecker->isGranted('MASTER', $processo)) {
                $usuario = $tokenStorage->getToken()->getUser();
                $aclObject->insertObjectAce(
                    UserSecurityIdentity::fromAccount($usuario),
                    MaskBuilder::MASK_MASTER
                );
                $aclProvider->updateAcl($aclObject);
            }

            $fundamentacaoRestricaoResource->save($fundamentacaoRestricao, $transactionId);
            $this->transactionManager->commit($transactionId);
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
     * Endpoint action to remover um direito de acesso sobre um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{processoId}/visibilidade/{visibilidadeId}',
        requirements: [
            'processoId' => '\d+',
            'visibilidadeId' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function destroyVisibilidadeAction(
        Request $request,
        int $processoId,
        int $visibilidadeId,
        FundamentacaoRestricaoResource $fundamentacaoRestricaoResource,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ProcessoResource $processoResource,
        UsuarioRepository $usuarioRepository,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $processo = $this->getResource()->getRepository()->find($processoId);

        if (!$authorizationChecker->isGranted('MASTER', $processo)) {
            throw new AccessDeniedException();
        }

        try {
            $transactionId = $this->transactionManager->begin();
            $objectIdentity = ObjectIdentity::fromDomainObject($processo);
            $aclObject = $aclProvider->findAcl($objectIdentity);

            $aceId = null;
            $fundamentacaoRestricao = null;
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

                    
                    if ($ace->getSecurityIdentity() instanceof UserSecurityIdentity) {
                        $user = $usuarioRepository
                            ->findOneBy(['username' => $ace->getSecurityIdentity()->getUsername()]);
                        $fundamentacaoRestricao = $processo->getFundamentacoesRestricao()->findFirst(
                            fn(int $key, FundamentacaoRestricao $fr) => $fr->getUsuario() &&
                                $fr->getUsuario()->getId() === $user->getId()
                        );
                    } else {
                        $roles = explode('_', (string) $ace->getSecurityIdentity()->getRole());
                        switch ($roles[1]) {
                            case 'SETOR':
                                $fundamentacaoRestricao = $processo->getFundamentacoesRestricao()->findFirst(
                                    fn(int $key, FundamentacaoRestricao $fr) => $fr->getSetor() &&
                                        $fr->getSetor()->getId() === (int) $roles[2]
                                );
                                break;
                            case 'UNIDADE':
                                $fundamentacaoRestricao = $processo->getFundamentacoesRestricao()->findFirst(
                                    fn(int $key, FundamentacaoRestricao $fr) => $fr->getUnidade() &&
                                        $fr->getUnidade()->getId() === (int) $roles[2]
                                );
                                break;
                        }
                    }
                }
            }

            if ($fundamentacaoRestricao) {
                $fundamentacaoRestricaoResource->delete($fundamentacaoRestricao->getId(), $transactionId);
            }

            // se ficar sem nenhuma, tem que recolocar a default
            if ([] === $aclObject->getObjectAces()) {
                $securityIdentity = new RoleSecurityIdentity('ROLE_USER');
                $aclObject->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($aclObject);

                foreach ($processo->getFundamentacoesRestricao() as $currentFundamentacaoRestricao) {
                    if (!$fundamentacaoRestricao ||
                        $fundamentacaoRestricao->getId() === $currentFundamentacaoRestricao->getId()
                    ) {
                        $fundamentacaoRestricaoResource
                            ->delete($currentFundamentacaoRestricao->getId(), $transactionId);
                    }
                }
            }

            // o usuário que restringe sempre deve ser master
            if (!$authorizationChecker->isGranted('MASTER', $processo)) {
                $usuario = $tokenStorage->getToken()->getUser();
                $aclObject->insertObjectAce(
                    UserSecurityIdentity::fromAccount($usuario),
                    MaskBuilder::MASK_MASTER
                );
                $aclProvider->updateAcl($aclObject);
            }

            $this->transactionManager->commit($transactionId);
            return new JsonResponse(
                [
                    'id' => $aceId,
                ]
            );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $processoId);
        }
    }

    /**
     * Endpoint action to arquivar um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/arquivar',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function arquivarAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

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

            $processoResource = $this->getResource();
            $processoDTO = $processoResource->getDtoForEntity($id, Processo::class);
            $processoEntity = $processoResource->arquivar($id, $processoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $processoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to autuar um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{id}/autuar',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PATCH']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function autuarAction(
        Request $request,
        int $id,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['PATCH'];

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

            $processoResource = $this->getResource();
            $processoDTO = $processoResource->getDtoForEntity($id, Processo::class);
            $processoEntity = $processoResource->autuar($id, $processoDTO, $transactionId, true);

            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()->createResponse($request, $processoEntity);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to download.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}/download/{tipo}/{sequencial}',
        requirements: [
            'id' => '\d+',
        ],
        defaults: [
            'sequencial' => 'all',
            'tipo' => 'PDF',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function downloadAction(
        Request $request,
        int $id,
        ?string $tipo,
        ?string $sequencial,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

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

            $this->transactionManager->addContext(
                new Context('tipoDownload', strtoupper($tipo)),
                $transactionId
            );

            $this->transactionManager->addContext(
                new Context('sequencial', $sequencial),
                $transactionId
            );

            $processo = $this->getResource()->download($id, $transactionId);
            $this->transactionManager->commit($transactionId);

            return $this->getResponseHandler()
                ->createResponse($request, $processo);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to imprimir etiqueta.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/imprime_etiqueta/{processoId}',
        requirements: [
            'processoId' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function imprimirEtiquetaAction(
        Request $request,
        int $processoId,
        Environment $twig,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        try {
            $componenteDigitalDTO = new ComponenteDigital();
            $processo = $this->getResource()->getRepository()->find($processoId);

            if (null !== $processo) {
                $conteudoHTML = $twig->render(
                    'Resources/Processo/layout_etiqueta.html.twig',
                    [
                        'processo' => $processo,
                    ]
                );

                $componenteDigitalDTO->setConteudo($conteudoHTML);
            }

            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse($request, $componenteDigitalDTO);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $processoId);
        }
    }

    /**
     * Endpoint action to imprimir relatorio de documentos.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    #[Route(
        path: '/imprime_relatorio/{processoId}',
        requirements: [
            'processoId' => '\d+',
        ],
        methods: ['GET']
    )]
    #[RestApiDoc]
    #[IsGranted('ROLE_COLABORADOR')]
    public function imprimirRelatorioAction(
        Request $request,
        int $processoId,
        Environment $twig,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        $contexto = RequestHandler::getContext($request);

        try {
            $componenteDigitalDTO = new ComponenteDigital();
            $processo = $this->getResource()->getRepository()->find($processoId);

            if (null !== $processo) {
                $tarefas = $processo->getTarefas();
                $arrTarefas = $tarefas->toArray();
                usort($arrTarefas, function ($a, $b) {
                    return $a->getDataHoraInicioPrazo() <=> $b->getDataHoraInicioPrazo();
                });
                $tarefas = new ArrayCollection($arrTarefas);

                $conteudoHTML = $twig->render(
                    'Resources/Processo/relatorio.html.twig',
                    [
                        'processo' => $processo,
                        'tarefas' => $tarefas,
                        'contexto' => $contexto,
                    ]
                );

                $componenteDigitalDTO->setConteudo($conteudoHTML);
            }

            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse($request, $componenteDigitalDTO);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $processoId);
        }
    }

    /**
     * Endpoint action para localizar uma processo no elasticsearch.
     *
     * @throws Throwable
     */
    #[Route(path: '/search', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    #[RestApiDoc]
    public function searchAction(Request $request, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $populate = RequestHandler::getPopulate($request, $this->getResource());

        try {
            $criteria = RequestHandler::getCriteria($request);

            $this->elasticQueryBuilderService->init(
                'processo'
            );

            $boolQuery = $this->elasticQueryBuilderService->proccessCriteria($criteria);

            $search = $this->processoIndex->createSearch()->addQuery($boolQuery);

            foreach ($orderBy as $key => $value) {
                if ($key && $value) {
                    $search->addSort(
                        new FieldSort(
                            $this->elasticQueryBuilderService->processaProperty($key),
                            null,
                            [
                                'order' => $value,
                            ]
                        )
                    );
                }
            }

            $search->setSize($limit);
            $search->setFrom($offset);
            $search->setTrackTotalHits(true);
            $search->setSource(false);

            $results = $this->processoIndex->findRaw($search);

            $result = [];
            $result['entities'] = [];

            foreach ($results as $document) {
                $entity = $this->getResource()->getRepository()->find((int) $document['_id'], $populate);
                if ($entity) {
                    $result['entities'][] = $entity;
                }
            }

            $result['total'] = $results->count();

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $result
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }

    /**
     * Endpoint action to get timeline json.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route('/{id}/timeline', requirements: ['id' => "\d+"], methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[OA\Tag('Processo')]
    #[OA\Response(
        response: 200,
        description: 'Endpoint action to fetch Processo timeline',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        'entities' => new OA\Property(
                            'entities',
                            description: 'array of entities',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    'timelineEvent' => new OA\Property(
                                        'timelineEvent',
                                        ref: new Model(type: TimelineEvent::class)
                                    ),
                                ]
                            )
                        ),
                        'total' => new OA\Property(
                            'total',
                            description: 'total os entities',
                            type: 'int',
                            example: '10'
                        ),
                    ],
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
                    'code' => new OA\Property(
                        'code',
                        description: 'Error code',
                        type: 'integer',
                    ),
                    'message' => new OA\Property(
                        'message',
                        description: 'Error description',
                        type: 'string',
                    ),
                ],
                type: 'object',
                example: [
                    'code' => 400,
                    'message' => 'Bad Request',
                ],
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
                    'code' => new OA\Property(
                        'code',
                        description: 'Error code',
                        type: 'integer',
                    ),
                    'message' => new OA\Property(
                        'message',
                        description: 'Error description',
                        type: 'string',
                    ),
                ],
                type: 'object',
                example: [
                    'code' => 401,
                    'message' => 'Bad credentials',
                ],
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
                    'code' => new OA\Property(
                        'code',
                        description: 'Error code',
                        type: 'integer',
                    ),
                    'message' => new OA\Property(
                        'message',
                        description: 'Error description',
                        type: 'string',
                    ),
                ],
                type: 'object',
                example: [
                    'code' => 404,
                    'message' => 'Not Found',
                ],
            )
        )
    )]
    public function getTimelineAction(
        Request $request,
        int $id,
        ?int $emptyUserEvents,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);
        $processo = $this->getResource()->getRepository()->find($id);

        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);

        try {
            $timeline = $this->paginator->paginate(
                $this->timelineProcessoService
                ->getTimelineProcesso(
                    $processo,
                    RequestHandler::getCriteria($request)
                ),
                (int) floor($offset / $limit) + 1,
                $limit
            );

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    [
                        'entities' => $timeline,
                        'total' => $timeline->getTotalItemCount()
                    ]
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }

    /**
     * Endpoint action to remover um direito de acesso sobre os documentos de um processo.
     *
     * @param string[]|null $allowedHttpMethods
     *
     * @throws LogicException
     * @throws Throwable
     * @throws HttpException
     * @throws MethodNotAllowedHttpException
     */
    #[Route(
        path: '/{processoId}/deletevisibilidadedocs',
        requirements: [
            'processoId' => '\d+',
        ],
        methods: ['DELETE']
    )]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function destroyVisibilidadeDocumentosAction(
        Request $request,
        int $processoId,
        JuntadaRepository $juntadaRepository,
        AclProviderInterface $aclProvider,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $juntadas = $juntadaRepository->findJuntadaByProcesso($processoId);

        $docsId = [];
        $docsErrors = [];

        foreach ($juntadas as $juntada) {
            $documento = $juntada->getDocumento();

            if (!$documento) {
                continue;
            }

            if ($documento && !$authorizationChecker->isGranted('MASTER', $documento)) {
                $error = $documento->getId();
                $docsErrors[] = $error;

                continue;
            }

            try {
                $objectIdentity = ObjectIdentity::fromDomainObject($documento);
                $aclObject = $aclProvider->findAcl($objectIdentity);

                if (128 === $aclObject->getObjectAces()[0]->getMask()) {
                    continue;
                }

                /**
                 * @var Entry $ace
                 */
                foreach ($aclObject->getObjectAces() as $ace) {
                    if (0 !== $ace->getMask()) {
                        $ace->getId();
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

                $docsId[] = $documento->getId();
            } catch (Throwable $exception) {
                throw $this->handleRestMethodException($exception, $documento->getId());
            }
        }

        return new JsonResponse(
            [
                'id' => $docsId,
                'errors' => $docsErrors,
            ]
        );
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
