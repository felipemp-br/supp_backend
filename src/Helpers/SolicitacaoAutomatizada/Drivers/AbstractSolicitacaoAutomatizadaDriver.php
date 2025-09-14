<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers;

use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0019;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0029;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;
use SuppCore\AdministrativoBackend\Enums\DossieVisibilidade;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnaliseDossieManager;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\NotFoundTipoDossieException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\ResourceUnavailableException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Extratores\ExtratorMetadadosDocumentoManager;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\DossiesNecessarios;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Repository\TipoDossieRepository;
use SuppCore\AdministrativoBackend\Rules\RulesManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * AbstractSolicitacaoAutomatizadaDriver.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractSolicitacaoAutomatizadaDriver
{
    public const string SIGLA_FORMULARIO_SOLICITACAO_AUTOMATIZADA = 'formulario_solicitacao_automatizada';

    /**
     * Constructor.
     *
     * @param AnaliseDossieManager              $analiseDossieManager
     * @param ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager
     * @param SolicitacaoAutomatizadaResource   $solicitacaoAutomatizadaResource
     * @param SerializerInterface               $serializer
     * @param TransactionManager                $transactionManager
     * @param LoggerInterface                   $logger
     * @param SuppParameterBag                  $suppParameterBag
     * @param PessoaResource                    $pessoaResource
     * @param TipoDossieRepository              $tipoDossieRepository
     * @param DossieResource                    $dossieResource
     * @param VinculacaoEtiquetaResource        $vinculacaoEtiquetaResource
     * @param EtiquetaResource                  $etiquetaResource
     * @param TipoDocumentoResource             $tipoDocumentoResource
     * @param DocumentoResource                 $documentoResource
     * @param ComponenteDigitalResource         $componenteDigitalResource
     * @param AclProviderInterface              $aclProvider
     * @param TarefaResource                    $tarefaResource
     * @param SetorResource                     $setorResource
     * @param EspecieTarefaResource             $especieTarefaResource
     * @param RulesManager                      $rulesManager
     */
    public function __construct(
        protected readonly AnaliseDossieManager $analiseDossieManager,
        protected readonly ExtratorMetadadosDocumentoManager $extratorMetadadosDocumentoManager,
        protected readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource,
        protected readonly SerializerInterface $serializer,
        protected readonly TransactionManager $transactionManager,
        protected readonly LoggerInterface $logger,
        protected readonly SuppParameterBag $suppParameterBag,
        protected readonly PessoaResource $pessoaResource,
        protected readonly TipoDossieRepository $tipoDossieRepository,
        protected readonly DossieResource $dossieResource,
        protected readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        protected readonly EtiquetaResource $etiquetaResource,
        protected readonly TipoDocumentoResource $tipoDocumentoResource,
        protected readonly DocumentoResource $documentoResource,
        protected readonly ComponenteDigitalResource $componenteDigitalResource,
        protected readonly AclProviderInterface $aclProvider,
        protected readonly TarefaResource $tarefaResource,
        protected readonly SetorResource $setorResource,
        protected readonly EspecieTarefaResource $especieTarefaResource,
        protected readonly RulesManager $rulesManager,
    ) {
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     *
     * @throws NotFoundTipoDossieException
     */
    public function processaStatus(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        match ($solicitacaoDTO->getStatus()) {
            StatusSolicitacaoAutomatizada::CRIADA => $this->processaStatusCriada(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES => $this->processaStatusSolicitandoDossies(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::ANDAMENTO => $this->processaStatusAndamento(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::ANALISANDO_REQUISITOS => $this->processaStatusAnalisandoRequisitos(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::ANALISE_PROCURADOR => $this->processaStatusAnaliseProcurador(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::DEFERIDO => $this->processaStatusDeferido(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::INDEFERIDO => $this->processaStatusIndeferido(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::DADOS_CUMPRIMENTO => $this->processaStatusDadosCumprimento(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::AGUARDANDO_CUMPRIMENTO => $this->processaStatusAguardandoCumprimento(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::SOLICITACAO_ATENDIDA => $this->processaStatusSolicitacaoAtendida(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::SOLICITACAO_NAO_ATENDIDA => $this->processaStatusSolicitacaoNaoAtendida(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO => $this->processaStatusErroSolicitacao(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            default => throw new Exception(
                sprintf(
                    'Erro ao processar status %s da solicitação automatizada',
                    $solicitacaoDTO->getStatus()?->value
                ),
                400
            ),
        };
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusCriada(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES);
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     *
     * @throws NotFoundTipoDossieException
     */
    protected function processaStatusSolicitandoDossies(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $solicitacaoDTO->setStatus(
            $this->verificaStatusDossies(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            )
        );

        if ($solicitacaoDTO->getStatus() === StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO) {
            $this->processaStatus(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
        }
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    abstract protected function processaStatusAndamento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void;

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusAnalisandoRequisitos(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        try {
            $analisesDossies = $this->deserializeAnaliseDossies($solicitacaoDTO->getAnalisesDossies());
            foreach ($analisesDossies as $analiseDossies) {
                if ($analiseDossies->getAnaliseExecutada() === false) {
                    $this->analiseDossieManager->analisar(
                        $analiseDossies,
                        $solicitacaoEntity->getTipoSolicitacaoAutomatizada(),
                        json_decode(
                            $solicitacaoDTO->getDadosTipoSolicitacao(),
                            true
                        ) ?? [],
                    );
                    $analiseDossies->setAnaliseExecutada(true);
                }
            }
            $solicitacaoDTO
                ->setAnalisesDossies(
                    $this->serializeAnaliseDossies($analisesDossies)
                )
                ->setStatus(StatusSolicitacaoAutomatizada::ANDAMENTO);
        } catch (ResourceUnavailableException $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'message' => $e->getPrevious()->getMessage(),
                    'code' => $e->getPrevious()->getCode(),
                    'trace' => $e->getPrevious()->getTraceAsString(),
                ]
            );
            throw $e;
        } catch (Throwable $e) {
            $this->logger->error(
                sprintf(
                    'Erro ao realizar analise dos dossies da solicitação automatizada %s',
                    $solicitacaoEntity->getId()
                ),
                [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
            $solicitacaoDTO->setStatus(StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO);
            $this->processaStatus(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            );
        }
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusAnaliseProcurador(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- @todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusDeferido(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusIndeferido(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusAguardandoCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusDadosCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusSolicitacaoAtendida(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusSolicitacaoNaoAtendida(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        //o- todo
    }

    /**
     * Processa a mudança de status da SolicitacaoAutomatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaStatusErroSolicitacao(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $this->createTarefa(
            $this->buildTarefaErroSolicitacao(
                $solicitacaoEntity,
                $solicitacaoDTO
            ),
            $transactionId
        );
    }

    /**
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     *
     * @return TarefaDTO
     */
    public function buildTarefaErroSolicitacao(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
    ): TarefaDTO {
        $config = $this->getConfigModuloSolicitacaoAutomatizada();
        $setorEntity = $this->setorResource->findOne(
            $config['setor_erro_solicitacao'],
        );
        $especieTarefaEntity = $this->especieTarefaResource->findOneBy([
            'nome' => $config['especie_tarefa_erro_solicitacao'],
        ]);

        return (new TarefaDTO())
            ->setProcesso($solicitacaoEntity->getProcesso())
            ->setDataHoraInicioPrazo(new DateTime())
            ->setSetorResponsavel($setorEntity)
            ->setEspecieTarefa($especieTarefaEntity);
    }

    /**
     * @param TarefaDTO $tarefaDTO
     * @param string $transactionId
     *
     * @return TarefaEntity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createTarefa(
        TarefaDTO $tarefaDTO,
        string $transactionId
    ): TarefaEntity {
        $this->rulesManager->disableRule(Rule0029::class);
        $this->rulesManager->disableRule(Rule0019::class);
        $tarefaEntity = $this->tarefaResource->create(
            $tarefaDTO,
            $transactionId
        );
        $this->rulesManager->enableRule(Rule0029::class);
        $this->rulesManager->enableRule(Rule0019::class);
        return $tarefaEntity;
    }

    /**
     * Verifica o status da geração dos dossies.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return StatusSolicitacaoAutomatizada
     *
     * @throws NotFoundTipoDossieException
     */
    protected function verificaStatusDossies(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): StatusSolicitacaoAutomatizada {
        $dossiesNecessarios = $this->deserializeDossiesNecessarios(
            $solicitacaoDTO->getDossiesNecessarios()
        );
        if (!empty($dossiesNecessarios)) {
            foreach ($dossiesNecessarios as $key => $item) {
                $this->geraDossies(
                    $item,
                    $solicitacaoEntity,
                    $transactionId
                );
                unset($dossiesNecessarios[$key]);
            }
            $solicitacaoDTO->setDossiesNecessarios(
                $this->serializeDossiesNecessarios($dossiesNecessarios)
            );

            return StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES;
        }
        $totalDossies = $solicitacaoEntity->getDossies()->count();
        $statusDossies = $this->getCountStatusGeracaoDossies($solicitacaoEntity);

        return match (true) {
            $statusDossies[DossieEntity::ERRO] > 0 => $this->processaStatusDossiesErro(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            $statusDossies[DossieEntity::EM_SINCRONIZACAO] > 0 => $this->processaStatusDossiesEmSincronizacao(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
            $totalDossies === $statusDossies[DossieEntity::SUCESSO] => $this->processaStatusDossiesSucesso(
                $solicitacaoEntity,
                $solicitacaoDTO,
                $transactionId
            ),
        };
    }

    /**
     * Processa o status de dossies em sincronização.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return StatusSolicitacaoAutomatizada
     */
    protected function processaStatusDossiesEmSincronizacao(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): StatusSolicitacaoAutomatizada {
        $this->logger->info(
            sprintf('A solicitação id %s possue dossies com status em sincronização.', $solicitacaoEntity->getId())
        );

        return StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES;
    }

    /**
     * Processa o status de dossies com erro.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return StatusSolicitacaoAutomatizada
     */
    protected function processaStatusDossiesErro(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): StatusSolicitacaoAutomatizada {
        $this->logger->warning(
            sprintf(
                'A solicitação id %s possue dossies com status de erros.',
                $solicitacaoEntity->getId(),
            )
        );

        return StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO;
    }

    /**
     * Processa o status de dossies com sucesso.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return StatusSolicitacaoAutomatizada
     */
    protected function processaStatusDossiesSucesso(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): StatusSolicitacaoAutomatizada {
        $this->logger->info(
            sprintf(
                'A solicitação id %s possue todos os dossies gerados com status de sucesso.',
                $solicitacaoEntity->getId()
            ),
        );

        return StatusSolicitacaoAutomatizada::ANDAMENTO;
    }

    /**
     * Processa a analise positiva dos requisitos.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    public function processaAnaliseRequisitosPositiva(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $solicitacaoDTO->setSugestaoDeferimento(true);
        $config = $this->getConfigModuloSolicitacaoAutomatizada();
        if (!empty($config['analise_positiva']['etiquetas_processos'])) {
            $etiquetaResult = $this->etiquetaResource->find(
                [
                    'nome' => sprintf(
                        'in:%s',
                        implode(',', $config['analise_positiva']['etiquetas_processos'])
                    ),
                    'modalidadeEtiqueta.valor' => 'eq:PROCESSO',
                ]
            );
            foreach ($etiquetaResult['entities'] as $etiqueta) {
                $this->vinculacaoEtiquetaResource->create(
                    (new VinculacaoEtiqueta())
                        ->setProcesso($solicitacaoDTO->getProcesso())
                        ->setEtiqueta($etiqueta),
                    $transactionId
                );
            }
        }
    }

    /**
     * Processa a analise negativa dos requisitos.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param string                        $transactionId
     *
     * @return void
     */
    public function processaAnaliseRequisitosNegativa(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        string $transactionId
    ): void {
        $solicitacaoDTO->setSugestaoDeferimento(false);
        $config = $this->getConfigModuloSolicitacaoAutomatizada();
        if (!empty($config['analise_negativa']['etiquetas_processos'])) {
            $etiquetaResult = $this->etiquetaResource->find(
                [
                    'nome' => sprintf(
                        'in:%s',
                        implode(',', $config['analise_negativa']['etiquetas_processos'])
                    ),
                    'modalidadeEtiqueta.valor' => 'eq:PROCESSO',
                ]
            );
            foreach ($etiquetaResult['entities'] as $etiqueta) {
                $this->vinculacaoEtiquetaResource->create(
                    (new VinculacaoEtiqueta())
                        ->setProcesso($solicitacaoDTO->getProcesso())
                        ->setEtiqueta($etiqueta),
                    $transactionId
                );
            }
        }
    }

    /**
     * Remove caracteres não númericos.
     *
     * @param string $value
     *
     * @return string
     */
    public static function somenteNumeros(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * @param DossiesNecessarios[] $dossiesNecessarios
     *
     * @return string
     */
    public function serializeDossiesNecessarios(array $dossiesNecessarios): string
    {
        return $this->serializer->serialize($dossiesNecessarios, 'json');
    }

    /**
     * @param string $jsonString
     *
     * @return DossiesNecessarios[]
     */
    public function deserializeDossiesNecessarios(string $jsonString): array
    {
        return $this->serializer->deserialize(
            $jsonString,
            sprintf(
                '%s[]',
                DossiesNecessarios::class,
            ),
            'json'
        );
    }

    /**
     * @param AnaliseDossies[] $analisesDossies
     *
     * @return string
     */
    public function serializeAnaliseDossies(array $analisesDossies): string
    {
        return $this->serializer->serialize($analisesDossies, 'json');
    }

    /**
     * @param string|null $jsonString
     *
     * @return AnaliseDossies[]
     */
    public function deserializeAnaliseDossies(?string $jsonString): array
    {
        $jsonString ??= '[]';
        return $this->serializer->deserialize(
            $jsonString,
            sprintf(
                '%s[]',
                AnaliseDossies::class,
            ),
            'json'
        );
    }

    /**
     * Gera os dossies.
     *
     * @param DossiesNecessarios            $dossiesNecessarios
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param string                        $transactionId
     *
     * @return void
     *
     * @throws NotFoundTipoDossieException
     */
    public function geraDossies(
        DossiesNecessarios $dossiesNecessarios,
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        string $transactionId
    ): void {
        $this->logger->info(
            sprintf(
                'Solicitando dossies do CPF %s para a solicitação id %s.',
                $dossiesNecessarios->getCpf(),
                $solicitacaoEntity->getId()
            )
        );
        $dossies = array_reduce(
            $this->tipoDossieRepository->findBy(['sigla' => $dossiesNecessarios->getSiglasDossies()]),
            function ($items, TipoDossieEntity $tipoDossie) {
                $items[$tipoDossie->getSigla()] = $tipoDossie;

                return $items;
            },
            []
        );
        foreach ($dossiesNecessarios->getSiglasDossies() as $siglaDossie) {
            if (!isset($dossies[$siglaDossie])) {
                throw new NotFoundTipoDossieException($siglaDossie);
            }
            $dossieDTO = (new DossieDTO())
                ->setTipoDossie($dossies[$siglaDossie])
                ->setSolicitacaoAutomatizada($solicitacaoEntity)
                ->setNumeroDocumentoPrincipal($dossiesNecessarios->getCpf())
                ->setPessoa(
                    $this->pessoaResource->findPessoaAdvanced($dossiesNecessarios->getCpf(), $transactionId)
                )
                ->setProcesso($solicitacaoEntity->getProcesso())
                ->setVisibilidade(DossieVisibilidade::RESTRITO_UNIDADE->value);
            $this->dossieResource->create($dossieDTO, $transactionId);
        }
    }

    /**
     * Retorna a quantidade de dossies por status.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     *
     * @return array<int, int>
     */
    public function getCountStatusGeracaoDossies(SolicitacaoAutomatizadaEntity $solicitacaoEntity): array
    {
        $config = $this->getConfigModuloSolicitacaoAutomatizada();
        $currentDate = new DateTime();

        return array_reduce(
            $solicitacaoEntity->getDossies()->toArray(),
            function (array $data, DossieEntity $dossie) use ($currentDate, $config) {
                $status = $dossie->getVersao() ?? 0;
                if ($status === DossieEntity::EM_SINCRONIZACAO) {
                    $diff = $dossie->getCriadoEm()->diff($currentDate);
                    if ($diff->days >= $config['prazo_timeout_verificacao_dossies']) {
                        $status = DossieEntity::ERRO;
                    }
                }
                ++$data[$status];

                return $data;
            },
            [
                DossieEntity::SUCESSO => 0,
                DossieEntity::ERRO => 0,
                DossieEntity::EM_SINCRONIZACAO => 0,
            ]
        );
    }

    /**
     * Agrupa os dossies por numero do documento principal.
     *
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     *
     * @return array<string,DossieEntity[]>
     */
    public function agrupaDossiesPorDocumentoPrincipal(SolicitacaoAutomatizadaEntity $solicitacaoEntity): array
    {
        return array_reduce(
            $solicitacaoEntity->getDossies()->toArray(),
            function (array $data, DossieEntity $dossie) {
                if (!isset($data[$dossie->getNumeroDocumentoPrincipal()])) {
                    $data[$dossie->getNumeroDocumentoPrincipal()] = [];
                }
                $data[$dossie->getNumeroDocumentoPrincipal()][] = $dossie;

                return $data;
            },
            []
        );
    }

    /**
     * Retorna as configurações do módulo de solicitações automatizadas.
     *
     * @return array
     */
    public function getConfigModuloSolicitacaoAutomatizada(): array
    {
        return $this->suppParameterBag->get('supp_core.administrativo_backend.solicitacao_automatizada');
    }

    /**
     * @param string            $conteudo
     * @param string            $transactionId
     * @param Processo|null     $processo
     * @param TarefaEntity|null $tarefa
     * @param SetorEntity|null  $setorOrigem
     * @param bool              $restrito
     *
     * @return DocumentoEntity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function criarDocumento(
        string $conteudo,
        string $transactionId,
        ?Processo $processo = null,
        ?TarefaEntity $tarefa = null,
        ?SetorEntity $setorOrigem = null,
        bool $restrito = false,
    ): DocumentoEntity {
        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento(
            $this->tipoDocumentoResource->findOneBy([
            'nome' => 'OFÍCIO',
            ])
        );
        if ($processo) {
            $documentoDTO->setProcessoOrigem($processo);
            $documentoDTO->setSetorOrigem($processo->getSetorAtual());
        } elseif ($tarefa) {
            $documentoDTO->setTarefaOrigem($tarefa);
            $documentoDTO->setSetorOrigem($tarefa->getProcesso()->getSetorAtual());
        }
        if ($setorOrigem) {
            $documentoDTO->setSetorOrigem($setorOrigem);
        }

        $documentoEntity = $this->documentoResource->create(
            $documentoDTO,
            $transactionId
        );

        if ($restrito) {
            $this->transactionManager->addAfterFlushFunctions(
                fn () => $this->gerarAclDocumentoUnidade($documentoEntity),
                $transactionId
            );
        }

         $componenteDigitalDTO = (new ComponenteDigital())
            ->setConteudo($conteudo)
            ->setMimetype('text/html')
            ->setExtensao('html')
            ->setEditavel(false)
            ->setFileName('REQUERIMENTO.html')
            ->setConteudo($conteudo)
            ->setHash(hash('SHA256', $conteudo))
            ->setTamanho(strlen($conteudo))
            ->setDocumento($documentoEntity);
         $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

         return $documentoEntity;
    }

    /**
     * @param DocumentoEntity $documento
     * @return void
     */
    public function gerarAclDocumentoUnidade(DocumentoEntity $documento): void
    {
        $objectIdentity = ObjectIdentity::fromDomainObject($documento);
        try {
            $aclObject = $this->aclProvider->findAcl($objectIdentity);
        } catch (Throwable $e) {
            $aclObject = $this->aclProvider->createAcl($objectIdentity);
        }

        // Para remover a regra padrão que libera para todos os usuários
        foreach ($aclObject->getObjectAces() as $index => $ace) {
            if (($ace->getSecurityIdentity() instanceof RoleSecurityIdentity)
            ) {
                $aclObject->deleteObjectAce($index);
                $this->aclProvider->updateAcl($aclObject);
            }
        }

        $maskBuilder = new MaskBuilder();
        $maskBuilder->add(MaskBuilder::MASK_MASTER);

        $securityIdentity = new RoleSecurityIdentity(
            'ACL_UNIDADE_'.$documento->getSetorOrigem()->getUnidade()->getId()
        );

        $aclObject->insertObjectAce($securityIdentity, $maskBuilder->get());
        $this->aclProvider->updateAcl($aclObject);
    }
}
