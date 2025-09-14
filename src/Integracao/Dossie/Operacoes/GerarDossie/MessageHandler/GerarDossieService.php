<?php /** @noinspection ALL */

declare(strict_types=1);

/**
 * src/Integracao/Dossie/Operacoes/GerarDossie/MessageHandler/GerarDossie.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossie\MessageHandler;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use DateTime;
use Psr\Log\LoggerInterface;
use Redis;
use Gedmo\Blameable\BlameableListener;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\OrigemDados as OrigemDadosEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;
use SuppCore\AdministrativoBackend\Enums\DossieVisibilidade;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieGerado;
use SuppCore\AdministrativoBackend\Security\InternalLogInService;
use SuppCore\GestaoDevedorBackend\Integracao\Dossie\TCU\GeradorDossie\GeradorDossieDatalake;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Acl\Domain\Entry;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use function strlen;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieManager;
use SuppCore\AdministrativoBackend\Integracao\Dossie\Operacoes\GerarDossie\Message\GerarDossieMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

/**
 * Class GerarDossieMessageHandler.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class GerarDossieService
{

    private const LOCK_WAIT_TIME = 10;
    private const LOCK_TRIES = 30;

    /**
     * GerarDossieMessageHandler constructor.
     *
     * @param BlameableListener $blameableListener
     * @param InternalLogInService $internalLogInService
     * @param TransactionManager $transactionManager
     * @param DossieManager $dossieManager
     * @param DossieResource $dossieResource
     * @param ProcessoResource $processoResource
     * @param DocumentoResource $documentoResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TipoDocumentoResource $tipoDocumentoResource
     * @param JuntadaResource $juntadaResource
     * @param VolumeResource $volumeResource
     * @param OrigemDadosResource $origemDadosResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param NotificacaoResource $notificacaoResource
     * @param ParameterBagInterface $parameterBag
     * @param Redis $redisClient
     * @param AclProviderInterface $aclProvider
     * @param Environment $twig
     * @param SuppParameterBag $suppParameterBag
     * @param LoggerInterface $logger
     */
    public function __construct(
        private BlameableListener $blameableListener,
        private InternalLogInService $internalLogInService,
        private TransactionManager $transactionManager,
        private DossieManager $dossieManager,
        private DossieResource $dossieResource,
        private ProcessoResource $processoResource,
        private DocumentoResource $documentoResource,
        private ComponenteDigitalResource $componenteDigitalResource,
        private TipoDocumentoResource $tipoDocumentoResource,
        private JuntadaResource $juntadaResource,
        private VolumeResource $volumeResource,
        private OrigemDadosResource $origemDadosResource,
        private ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        private NotificacaoResource $notificacaoResource,
        private ParameterBagInterface $parameterBag,
        private Redis $redisClient,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AclProviderInterface $aclProvider,
        private readonly Environment $twig,
        private readonly SuppParameterBag $suppParameterBag,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param TipoDossieEntity $tipoDossie
     * @param PessoaEntity $pessoa
     * @return DossieEntity|null
     */
    public function dossieSimilar(
        TipoDossieEntity $tipoDossie,
        PessoaEntity $pessoa
    ): ?DossieEntity {
        if (!$tipoDossie->getPeriodoGuarda()) {
            return null;
        }

        $dossie = $this
            ->dossieResource
            ->getRepository()
            ->findSimilarMostRecent($tipoDossie, $pessoa);

        return $dossie && (((new DateTime())->diff($dossie->getDataConsulta()))->days <= $tipoDossie->getPeriodoGuarda(
            )) ?
            $dossie :
            null;
    }

    /**
     * @param GerarDossieMessage $gerarDossieMessage
     * @return void
     * @throws Exception
     */
    public function gerarDossie(GerarDossieMessage $gerarDossieMessage)
    {
        $transactionId = $this->transactionManager->getCurrentTransactionId() ?: $this->transactionManager->begin();
        $dossieEntity = $this->dossieResource->findOneBy(['uuid' => $gerarDossieMessage->getDossieUuid()]);
        if (!$dossieEntity) {
            throw new Exception("Dossie não encontrado {$gerarDossieMessage->getDossieUuid()}");
        }

        if ($dossieEntity->getCriadoPor()) {
            $this->internalLogInService->logUserIn($dossieEntity->getCriadoPor());
            $this->blameableListener->setUserValue($dossieEntity->getCriadoPor());
        }

        $lockedSession = false;
        try {
            // geracao do dossie ou reaproveitamento
            $dossieSimilar = $this->dossieSimilar($dossieEntity->getTipoDossie(), $dossieEntity->getPessoa());
            if ($dossieSimilar?->getDocumento()?->getComponentesDigitais()?->first()) {
                $this->reaproveitaDossieSimilar($dossieEntity, $dossieSimilar, $transactionId);
            } else {
                $this->geraNovoDossie($dossieEntity, $transactionId, $gerarDossieMessage);
            }

            // juntada do dossie no processo
            if ($dossieEntity->getProcesso()) {
                $lockedSession = $this->lockAcessoProcesso($dossieEntity);
                $tentativaJuntada = 0;
                while ($tentativaJuntada <= 5) {
                    try {
                        $juntadaDTO = new Juntada();
                        $juntadaDTO->setDescricao(
                            "JUNTADA DOSSIE {$dossieEntity->getTipoDossie()->getNome()} REF. A {$dossieEntity->getPessoa()->getNome()}"
                        );
                        $juntadaDTO->setDocumento($dossieEntity->getDocumento());
                        $juntadaDTO->setVolume(
                            $this->volumeResource->getRepository()->findVolumeAbertoByProcessoId(
                                $dossieEntity->getProcesso()->getId()
                            )
                        );

                        $this->juntadaResource->create($juntadaDTO, $transactionId);
                        break;
                    } catch (\Throwable $e) {
                        $tentativaJuntada++;
                        $dossieEntity->getDocumento()->setJuntadaAtual(null);
                        if ($tentativaJuntada > 5) {
                            throw $e;
                        }
                        sleep(self::LOCK_WAIT_TIME);
                    }
                }
            }
            $this->alteraStatusOrigemDados($dossieEntity, DossieEntity::SUCESSO, $transactionId);

            if ($gerarDossieMessage->getSobDemanda()) {
                $modalidadeNotificacao = $this->modalidadeNotificacaoResource
                    ->findOneBy(
                        ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                    );

                $notificacaoDTO = (new Notificacao())
                    ->setDestinatario($dossieEntity->getCriadoPor())
                    ->setModalidadeNotificacao($modalidadeNotificacao)
                    ->setConteudo(
                        'Dossiê '.$dossieEntity->getTipoDossie()->getNome().
                        ' para: '.$dossieEntity->getPessoa()->getNome(
                        ).' criado com sucesso. NUP: '.$dossieEntity->getProcesso()->getNUP()
                    )
                    ->setTipoNotificacao(null);
                $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            }

            $this->transactionManager->commit();

            if ($dossieEntity->getVisibilidade()) {
                $this->restringirAcessoDossieDocumento($dossieEntity);

            }

            if ($lockedSession) {
                $this->unlockAcessoProcesso($dossieEntity);
            }
        } catch (Exception $e) {
            $this->logger->critical('Dossie:'.$dossieEntity->getId().':'.$e->getMessage().':'.$e->getTraceAsString());
            $this->transactionManager->resetTransaction();
            $transactionId = $this->transactionManager->getCurrentTransactionId() ?: $this->transactionManager->begin();
            $dossieEntity = $this->dossieResource->findOneBy(['uuid' => $gerarDossieMessage->getDossieUuid()]);

            if ($gerarDossieMessage->getSobDemanda()) {
                $modalidadeNotificacao = $this->modalidadeNotificacaoResource
                    ->findOneBy(
                        ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                    );
                $notificacaoDTO = (new NotificacaoDTO())
                    ->setDestinatario($dossieEntity->getCriadoPor())
                    ->setModalidadeNotificacao($modalidadeNotificacao)
                    ->setConteudo(
                        'ERRO AO GERAR '.$dossieEntity->getTipoDossie()->getNome().
                        ' DO NUP: '.$dossieEntity->getProcesso()->getNUP().' '.
                        $e->getMessage()
                    )
                    ->setTipoNotificacao(null);
                $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            }

            $this->alteraStatusOrigemDados($dossieEntity, DossieEntity::ERRO, $transactionId);
            $this->transactionManager->commit();
            if ($lockedSession) {
                $this->unlockAcessoProcesso($dossieEntity);
            }
            throw $e;
        }
    }

    private function restringirAcessoDossieDocumento(DossieEntity $dossieEntity): void
    {
        $objectIdentity = ObjectIdentity::fromDomainObject($dossieEntity->getDocumento());
        $aclObject = $this->aclProvider->findAcl($objectIdentity);

        $maskBuilder = new MaskBuilder();
        $maskBuilder->add(MaskBuilder::MASK_MASTER);

        switch ($dossieEntity->getVisibilidade()) {
            case DossieVisibilidade::RESTRITO_SETOR->value:
                $securityIdentity = new RoleSecurityIdentity(
                    'ACL_SETOR_'.$dossieEntity->getDocumento()->getSetorOrigem()->getId()
                );
                break;
            case DossieVisibilidade::RESTRITO_UNIDADE->value:
                $securityIdentity = new RoleSecurityIdentity(
                    'ACL_UNIDADE_'.$dossieEntity->getDocumento()->getSetorOrigem()->getUnidade()->getId()
                );
                break;
            default:
                $securityIdentity = null;
        }
        // Caso seja solicitação realizada sem usuário, removemos o usuário comum (visível à todos)
        if (!$this->tokenStorage->getToken()?->getUser() && null !== $securityIdentity) {
            /**
             * @var Entry $ace
             */
            foreach ($aclObject->getObjectAces() as $index => $ace) {
                // remove de todos os usuários
                if (($ace->getSecurityIdentity() instanceof RoleSecurityIdentity)
                    && ('ROLE_USER' === $ace->getSecurityIdentity()->getRole())
                    && in_array($ace->getMask(), [MaskBuilder::MASK_MASTER, MaskBuilder::MASK_OWNER], true)
                ) {
                    $aclObject->deleteObjectAce($index);
                    $this->aclProvider->updateAcl($aclObject);
                }
            }
        }

        $aclObject->insertObjectAce($securityIdentity, $maskBuilder->get());
        $this->aclProvider->updateAcl($aclObject);
    }

    /**
     * @param DossieEntity $dossieEntity
     * @param DossieEntity $dossieSimilar
     * @param string $transactionId
     * @return void
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function reaproveitaDossieSimilar(
        DossieEntity $dossieEntity,
        DossieEntity $dossieSimilar,
        string $transactionId
    ) {
        $componenteDigitalEntity = $dossieSimilar->getDocumento()->getComponentesDigitais()->first();
        $componenteDigitalEntity = $this
            ->componenteDigitalResource
            ->download(
                $componenteDigitalEntity->getId(),
                $transactionId,
                false
            );

        /** @var DossieDTO $dossieDTO */
        $dossieDTO = $this->dossieResource->getDtoForEntity(
            $dossieEntity->getId(),
            DossieDTO::class
        );
        $dossieDTO->setVersao($dossieSimilar->getVersao());
        $dossieDTO->setConteudo($dossieSimilar->getConteudo());
        $dossieDTO->setDataConsulta($dossieSimilar->getDataConsulta());
        $dossieDTO->setDocumento(
            $this->criarDocumento(
                $dossieEntity->getProcesso(),
                $componenteDigitalEntity->getFileName(),
                $componenteDigitalEntity->getConteudo(),
                'html',
                $this->criaOrigemDadosDocumento(
                    $dossieSimilar->getVersao(),
                    $dossieEntity,
                    $transactionId
                ),
                $dossieEntity->getVisibilidade(),
                $transactionId,
                $dossieEntity->getTipoDossie()?->getSigla()
            )
        );

        $this->dossieResource->update($dossieEntity->getId(), $dossieDTO, $transactionId);
    }

    /**
     * @param DossieEntity $dossieEntity
     * @param string $transactionId
     * @return void
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function geraNovoDossie(
        DossieEntity $dossieEntity,
        string $transactionId,
        ?GerarDossieMessage $gerarDossieMessage = null
    ) {
        $geradorDossie = $this
            ->dossieManager
            ->getGeradorDossiePorTipoDossie($dossieEntity->getTipoDossie());

        if (!$geradorDossie) {
            throw new Exception(
                "Não foi possível encontrar um serviço para este tipo de Dossie {$dossieEntity->getTipoDossie()->getNome()}!"
            );
        }

        if ($gerarDossieMessage->getSobDemanda() && !$geradorDossie->supportsSobDemanda($dossieEntity)) {
            throw new Exception(
                "Verifique se a unidade possui permissão para gerar e se o interessado é pessoa física."
            );
        }

        $nomeArquivo = "DOSSIE_{$dossieEntity->getTipoDossie()->getNome()}_{$dossieEntity->getPessoa()->getNumeroDocumentoPrincipal()}.html";
        $dossieGerado = $geradorDossie
            ->setData($gerarDossieMessage->getData())
            ->geraDossie($dossieEntity, $gerarDossieMessage->getGeraDocumento());

        // atualiza o dossie
        /** @var DossieDTO $dossieDTO */
        $dossieDTO = $this->dossieResource->getDtoForEntity(
            $dossieEntity->getId(),
            DossieDTO::class
        );
        $dossieDTO->setVersao($dossieGerado->getVersao());
        $dossieDTO->setConteudo($dossieGerado->getConteudo());
        $dossieDTO->setDocumento(
            $this->criarDocumento(
                $dossieEntity->getProcesso(),
                $nomeArquivo,
                $dossieGerado->getConteudoHtml(),
                $dossieGerado->getExtensao(),
                $this->criaOrigemDadosDocumento(
                    $dossieGerado->getVersao(),
                    $dossieEntity,
                    $transactionId
                ),
                $dossieEntity->getVisibilidade(),
                $transactionId,
                $dossieEntity->getTipoDossie()?->getSigla()
            )
        );
        $dossieDTO->setProtocoloRequerimento($dossieGerado->getProtocolo());
        $dossieDTO->setStatusRequerimento($dossieGerado->getStatus());

        $this->dossieResource->update($dossieEntity->getId(), $dossieDTO, $transactionId);
    }

    /**
     * @param ProcessoEntity|null $processo
     * @param string $nomeArquivo
     * @param string|null $conteudo
     * @param string|null $extensao
     * @param string $transactionId
     * @return DocumentoEntity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function criarDocumento(
        ?ProcessoEntity $processo,
        string $nomeArquivo,
        ?string $conteudo,
        ?string $extensao,
        ?OrigemDadosEntity $origemDados,
        ?int $visibilidade,
        string $transactionId,
        string $siglaDocumento = null
    ): DocumentoEntity {
        $documentoDto = new DocumentoDTO();
        $documentoDto->setMinuta(false);
        $documentoDto->setOrigemDados($origemDados);

        // Compatibilidade SAPIENS/SUPP_AGU
        // $documentoDto->setVisibilidade((bool)$visibilidade);

        if ($processo) {
            $documentoDto->setSetorOrigem($processo->getSetorAtual());
        }

        $tipoDocumento = $this
            ->tipoDocumentoResource->findOneBy(['sigla' => $siglaDocumento]);

        if (!$tipoDocumento) {
            $tipoDocumento = $this
                ->tipoDocumentoResource
                ->getRepository()
                ->findByNomeAndEspecie('OUTROS', 'ADMINISTRATIVO');
        }

        $documentoDto->setTipoDocumento($tipoDocumento);

        $documentoEntity = $this
            ->documentoResource
            ->create($documentoDto, $transactionId);

        if ($conteudo) {
            switch ($extensao) {
                case 'pdf':
                    $mimeType = 'application/pdf';
                    $extensaoDocumento = 'pdf';
                    break;

                default:
                    $mimeType = 'text/html';
                    $extensaoDocumento = 'html';
                    break;
            }

            // cria o componente Digital
            $componenteDigitalDTO = new ComponenteDigital();
            $componenteDigitalDTO->setConteudo($conteudo);
            $componenteDigitalDTO->setMimetype($mimeType);
            $componenteDigitalDTO->setExtensao($extensaoDocumento);
            $componenteDigitalDTO->setEditavel(false);
            $componenteDigitalDTO->setFileName($nomeArquivo);
            $componenteDigitalDTO->setTamanho(strlen($conteudo));
            $componenteDigitalDTO->setNivelComposicao(3);
            $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
            $componenteDigitalDTO->setTamanho(strlen($componenteDigitalDTO->getConteudo()));
            $componenteDigitalDTO->setDocumento($documentoEntity);

            $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
        }

        return $documentoEntity;
    }

    /**
     * @param DossieEntity $dossieEntity
     * @param int $status
     * @param string $transactionId
     * @return void
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function alteraStatusOrigemDados(
        DossieEntity $dossieEntity,
        int $status,
        string $transactionId
    ) {
        /**
         * @var DossieDTO $restDto
         */
        $origemDadosDTO = $this
            ->origemDadosResource
            ->getDtoForEntity($dossieEntity->getOrigemDados()->getId(), OrigemDadosDTO::class);

        $origemDadosDTO->setStatus($status);
        $this->origemDadosResource->update($dossieEntity->getOrigemDados()->getId(), $origemDadosDTO, $transactionId);
    }


    protected function lockKey(DossieEntity $dossie): string
    {
        return $dossie::class."_".$dossie->getProcesso()->getNUP();
    }

    protected function lockAcessoProcesso(DossieEntity $dossie): bool
    {
        if ($dossie->getProcesso()) {
            for ($i = 0; $i < self::LOCK_TRIES; $i++) {
                // se existe o lock espera
                if ($this->redisClient->exists($this->lockKey($dossie))) {
                    sleep(self::LOCK_WAIT_TIME);
                } else {
                    // conseguiu pegar o lock
                    $this->redisClient->set($this->lockKey($dossie), true);
                    $this->redisClient->expire($this->lockKey($dossie), self::LOCK_WAIT_TIME * self::LOCK_TRIES);

                    return true;
                }
            }
        }

        // nao conseguiu pegar o lock
        return false;
    }

    protected function unlockAcessoProcesso(DossieEntity $dossie): void
    {
        if ($dossie->getProcesso()) {
            $this->redisClient->del($this->lockKey($dossie));
        }
    }

    /**
     * @param DossieGerado $dossieGerado
     * @param DossieEntity $dossieEntity
     * @return OrigemDadosEntity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function criaOrigemDadosDocumento(
        int $status,
        DossieEntity $dossieEntity,
        string $transactionId
    ): OrigemDadosEntity {
        /**
         * @var DossieDTO $restDto
         */
        $origemDadosDTO = new OrigemDados();
        $origemDadosDTO->setStatus($status);
        $origemDadosDTO->setFonteDados($dossieEntity->getTipoDossie()->getFonteDados());
        $origemDadosDTO->setIdExterno(
            "{$dossieEntity->getTipoDossie()->getFonteDados()}:{$dossieEntity->getNumeroDocumentoPrincipal()}"
        );
        $origemDadosDTO->setServico($dossieEntity->getTipoDossie()->getFonteDados());
        $origemDadosDTO->setDataHoraUltimaConsulta(new DateTime());

        return $this
            ->origemDadosResource
            ->create($origemDadosDTO, $transactionId);
    }
}
