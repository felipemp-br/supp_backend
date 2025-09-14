<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use ReflectionException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFaseResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeVinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\Barramento\Message\CriaTarefaBarramentoMessage;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase as ModalidadeFaseEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio as ModalidadeMeioEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso as ModalidadeVinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Tramitacao;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as StatusBarramentoEntity;
use SuppCore\AdministrativoBackend\Repository\EspecieTarefaRepository;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Classe responsável por sincronizar objetos diversos de processos entre o Sapiens e o Barramento.
 */
class BarramentoSincronizacaoProcesso
{
    use BarramentoUtil;
    use OrigemDados;

    private string $transactionId;

    private int $idt;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Mapeamento dos tipos de documentos utilizado no barramento.
     */
    private array $mapTipoDocumento;

    /**
     * Service para tratamento de transição de objetos Pasta.
     */
    private TransicaoResource $transicaoResource;

    /**
     * Service utilizada para criar e sincronizar interessados.
     */
    private BarramentoInteressadoManager $interessadoManager;

    /**
     * Service utilizada para criar e sincronizar documentos orindos do barramento.
     */
    private BarramentoDocumentoManager $documentoManager;

    /**
     * Response obtido por meio do request enviado ao Soap
     * Será utilizado para reuso dentro dos métodos das classes herdadas.
     */
    private stdClass $response;

    /**
     * Utilizado para armazenar o processo gerado por meio do processo principal do trâmite,
     * permitindo a recursividade de criação dos processos apensados ao principal.
     */
    private ?ProcessoEntity $processoPrincipal = null;

    /**
     * Setor destinatário dentro do SUPP
     * Utilizado para realizar Acl dos objetos Pasta e Documento com base no Setor Destino.
     */
    private SetorEntity $setorDestinatario;

    private OrigemDadosResource $origemDadosResource;

    private ProcessoResource $processoResource;

    private ClassificacaoResource $classificacaoResource;

    private VolumeResource $volumeResource;

    private TramitacaoResource $tramitacaoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private VinculacaoProcessoResource $vinculacaoProcessoResource;

    private JuntadaResource $juntadaResource;

    private ModalidadeVinculacaoProcessoResource $modalidadeVinculacaoProcessoResource;

    private TarefaResource $tarefaResource;

    private ModalidadeMeioResource $modalidadeMeioResource;

    private ModalidadeFaseResource $modalidadeFaseResource;

    private SetorResource $setorResource;

    private EspecieTarefaRepository $especieTarefaRepository;

    private EspecieProcessoRepository $especieProcessoRepository;

    private TransactionManager $transactionManager;

    private ModalidadeVinculacaoProcessoRepository $modalidadeVinculacaoProcessoRepository;

    private NUPProviderManager $nupProviderManager;

    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        TransicaoResource $transicaoResource,
        BarramentoInteressadoManager $interessadoManager,
        BarramentoDocumentoManager $documentoManager,
        OrigemDadosResource $origemDadosResource,
        ProcessoResource $processoResource,
        ClassificacaoResource $classificacaoResource,
        EspecieProcessoRepository $especieProcessoRepository,
        VolumeResource $volumeResource,
        TramitacaoResource $tramitacaoResource,
        StatusBarramentoResource $statusBarramentoResource,
        VinculacaoProcessoResource $vinculacaoProcessoResource,
        JuntadaResource $juntadaResource,
        ModalidadeVinculacaoProcessoResource $modalidadeVinculacaoProcessoResource,
        TarefaResource $tarefaResource,
        ModalidadeMeioResource $modalidadeMeioResource,
        ModalidadeFaseResource $modalidadeFaseResource,
        SetorResource $setorResource,
        EspecieTarefaRepository $especieTarefaRepository,
        TransactionManager $transactionManager,
        ModalidadeVinculacaoProcessoRepository $modalidadeVinculacaoProcessoRepository,
        NUPProviderManager $NUPProviderManager
    ) {
        $this->logger = $logger;
        $this->mapTipoDocumento =
            $parameterBag->get('integracao_barramento')['mapeamentos']['tipo_documento_barramento'];
        $this->transicaoResource = $transicaoResource;
        $this->interessadoManager = $interessadoManager;
        $this->documentoManager = $documentoManager;
        $this->origemDadosResource = $origemDadosResource;
        $this->processoResource = $processoResource;
        $this->classificacaoResource = $classificacaoResource;
        $this->volumeResource = $volumeResource;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->vinculacaoProcessoResource = $vinculacaoProcessoResource;
        $this->juntadaResource = $juntadaResource;
        $this->modalidadeVinculacaoProcessoResource = $modalidadeVinculacaoProcessoResource;
        $this->tarefaResource = $tarefaResource;
        $this->modalidadeMeioResource = $modalidadeMeioResource;
        $this->modalidadeFaseResource = $modalidadeFaseResource;
        $this->setorResource = $setorResource;
        $this->especieTarefaRepository = $especieTarefaRepository;
        $this->especieProcessoRepository = $especieProcessoRepository;
        $this->transactionManager = $transactionManager;
        $this->modalidadeVinculacaoProcessoRepository = $modalidadeVinculacaoProcessoRepository;
        $this->nupProviderManager = $NUPProviderManager;
    }

    /**
     * Sincroniza uma tramitação de processo recebida do barramento criando objetos no sapiens quando não existir.
     *
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function sincronizaProcesso(
        int $idt,
        stdClass $metadadosProcesso,
        PessoaEntity $pessoaRemetente,
        SetorEntity $setorDestinatario,
        string $nre,
        string $transactionId,
        stdClass $response
    ): ProcessoEntity {
        $this->idt = $idt;
        $this->response = $response;
        $this->transactionId = $transactionId;
        $this->setorDestinatario = $setorDestinatario;

        $protocoloSemFormatacao = preg_replace('/\D/', '', $metadadosProcesso->protocolo);

        /** @var ProcessoEntity $processoEntity */
        $processoEntity = $this->processoResource->findOneBy(['NUP' => $protocoloSemFormatacao]);

        if (!$processoEntity) {
            // verifica se o processo já existe pelo atributo outroNumero
            $processoEntity = $this->processoResource->findOneBy(['outroNumero' => $metadadosProcesso->protocolo]);

            if (!$processoEntity || ($processoEntity && !$processoEntity->getOrigemDados())) {
                $processoEntity = $this->criaProcesso($metadadosProcesso, $pessoaRemetente, $nre);
            }
        } elseif (!$processoEntity->getOrigemDados()) {
            // verifica se o processo ja foi remetido via barramento anteriormente, verificando as tramitacoes
            $tramitacaoBarramento = $processoEntity->getTramitacoes()
                ->filter(fn (Tramitacao $t) => $t->getMecanismoRemessa() === 'barramento');

            if($tramitacaoBarramento->count() > 0){
                $statusBarramento = $this->statusBarramentoResource->getRepository()
                    ->findBy(['processo' => $processoEntity->getId()]);
                $ultimoSucesso = array_filter($statusBarramento,
                    fn (StatusBarramento $sb) => in_array($sb->getCodSituacaoTramitacao(), [5,6]));
                if(!$ultimoSucesso){
                    $this->atualizaProcessoExistente($processoEntity, $nre, $metadadosProcesso);
                }
            } else {
                $this->atualizaProcessoExistente($processoEntity, $nre, $metadadosProcesso);
            }

        }

        $volume = $processoEntity->getVolumes()[0];

        if (!$volume) {
            $volume = (new ArrayCollection($this->transactionManager
                ->getScheduledEntities(Volume::class, $transactionId)))
                ->filter(fn (Volume $v) => $v->getProcesso()->getUuid() === $processoEntity->getUuid())
                ->first();
        }

        // tramitacao
        $this->sincronizaTramitacao($processoEntity);

        // Guarda processo principal do trâmite
        if ($processoEntity && !$this->processoPrincipal) {
            $this->atribuiProcessoPrincipal($processoEntity);
        }

        // documentos
        $this->documentoManager
            ->sincronizaDocumentos($metadadosProcesso, $processoEntity, $volume, $this->transactionId, $nre);

        // interessados
        $this->interessadoManager
            ->sincronizaInteressados($metadadosProcesso, $processoEntity, $this->transactionId, $nre);

        // Procedimento recursivo, para cada processo apensado o método sincronizaPasta é chamado novamente
        $this->sincronizaProcessosApensados($metadadosProcesso, $pessoaRemetente, $setorDestinatario, $nre);

        // cria tarefa async dispatch
        $protocolo = $this->setorResource->getRepository()
            ->findProtocoloInUnidade($this->setorDestinatario->getId());

        //verifica se o tramite foi para um setor ou unidade
        $setorResponsavel = $protocolo ?? $this->setorDestinatario;

//        $tarefaBarramentoMessage = new CriaTarefaBarramentoMessage();
//        $tarefaBarramentoMessage->setProcessoUuid($processoEntity->getUuid());
//        $tarefaBarramentoMessage->setSetorResponsavelId($setorResponsavel->getId());
//
//        $this->transactionManager->addAsyncDispatch($tarefaBarramentoMessage, $this->transactionId);
        // $this->criaTarefaDistribuidor($processoEntity);

        //coloca o Setor de Recebimento como o Setor Responsável do NUP
        $processoEntity->setSetorAtual($setorResponsavel);

        $this->processoPrincipal = null;
        return $processoEntity;
    }

    /**
     * Cria pasta a partir dos objetos gerados por meio dos metadados rebidos pelo barramento.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function criaProcesso(
        stdClass $metadadosProcesso,
        PessoaEntity $pessoaRemetente,
        string $nre
    ): ProcessoEntity {
        $processoDto = new ProcessoDTO();

        $processoDto->setUnidadeArquivistica(ProcessoEntity::UA_PROCESSO);
        $processoDto->setTipoProtocolo(ProcessoEntity::TP_INFORMADO);
        $processoDto->setTitulo(strtoupper($metadadosProcesso->descricao));
        $processoDto->setProcedencia($pessoaRemetente);

        $protocoloSetor = $this->setorResource->getRepository()
            ->findProtocoloInUnidade($this->setorDestinatario->getId());

        //verifica se o tramite foi para um setor ou unidade
        $setorAtual = $protocoloSetor ?? $this->setorDestinatario;

        $processoDto->setSetorAtual($setorAtual);

        $processoDto->setNUP(
            preg_replace('/\D/', '', $metadadosProcesso->protocolo)
        );
        $processoDto->setDataHoraAbertura(
            $this->converteDataBarramento($metadadosProcesso->dataHoraDeProducao)
        );

        // verifica Nup valido
        $validarNup = $this->nupProviderManager->getNupProvider(
            $processoDto
        )->validarNumeroUnicoProtocolo($processoDto);

        // caso numero nup for invalido
        if (!$validarNup) {
            $processoDto->setTipoProtocolo(ProcessoEntity::TP_NOVO);
            $processoDto->setOutroNumero($metadadosProcesso->protocolo);
            $processoDto->setNUP(
                $this->nupProviderManager->getNupProvider($processoDto)->gerarNumeroUnicoProtocolo($processoDto)
            );
        }

        $processoDto->setVisibilidadeExterna(false);
        // Adiciona restrições de acesso do tramite
        if (isset($metadadosProcesso->nivelDeSigilo) && 1 !== $metadadosProcesso->nivelDeSigilo) {
            $processoDto->setAcessoRestrito(true);
        }

        /** @var ClassificacaoEntity $classificacaoEntity */
        $classificacaoEntity = $this->classificacaoResource->findOneBy(['codigo' => '069']);
        $processoDto->setClassificacao($classificacaoEntity);

        /** @var EspecieProcessoEntity $especieProcessoEntity */
        $especieProcessoEntity = $this->especieProcessoRepository->findByNomeAndGenero(
            'COMUM',
            'ADMINISTRATIVO'
        );
        $processoDto->setEspecieProcesso($especieProcessoEntity);

        /** @var ModalidadeMeioEntity $modalidadeMeioEntity */
        $modalidadeMeioEntity = $this->modalidadeMeioResource->findOneBy(['valor' => 'ELETRÔNICO']);
        $processoDto->setModalidadeMeio($modalidadeMeioEntity);

        /** @var ModalidadeFaseEntity $modalidadeFaseEntity */
        $modalidadeFaseEntity = $this->modalidadeFaseResource->findOneBy(['valor' => 'CORRENTE']);
        $processoDto->setModalidadeFase($modalidadeFaseEntity);

        $processoDto->setChaveAcesso(substr(md5(uniqid((string) rand(), true)), 0, 8));

        $origemDadosDTo = $this->origemDadosFactory();
        $origemDadosDTo->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosDTo->setIdExterno($nre);

        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTo, $this->transactionId);
        $processoDto->setOrigemDados($origemDadosEntity);

        return $this->processoResource->create($processoDto, $this->transactionId);
    }

    /**
     * Atualiza processo existente.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function atualizaProcessoExistente(
        ProcessoEntity $processoEntity,
        string $nre,
        stdClass $metadadosProcesso
    ): ProcessoEntity {

        $processoDto = $this->processoResource->getDtoForEntity($processoEntity->getId(), ProcessoDTO::class);

        $origemDadosDTo = new \SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados();
        $origemDadosDTo->setServico('barramento_existente');
        $origemDadosDTo->setFonteDados('BARRAMENTO_PEN');
        $origemDadosDTo->setDataHoraUltimaConsulta(new DateTime());
        $origemDadosDTo->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosDTo->setIdExterno($nre);

        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTo, $this->transactionId);
        $processoDto->setOrigemDados($origemDadosEntity);

        // cria termo de inicio para marcar sincronizacao
        $this->documentoManager->criaTermoSincronizacao($processoEntity, $metadadosProcesso, $this->transactionId);

        return $this->processoResource->update($processoEntity->getId(), $processoDto, $this->transactionId);
    }

    /**
     * Cria tramitação.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function sincronizaTramitacao(ProcessoEntity $processoEntity): void
    {
        /** @var TramitacaoEntity $tramitacaoEntity */
        $tramitacaoEntity = $this->tramitacaoResource->getRepository()->findPendenteExternaProcesso(
            $processoEntity->getId()
        );

        if (!$tramitacaoEntity) {
            $tramitacaoDto = new TramitacaoDTO();
            $tramitacaoDto->setSetorOrigem($processoEntity->getSetorInicial());
            $tramitacaoDto->setPessoaDestino($processoEntity->getProcedencia());
            $tramitacaoDto->setProcesso($processoEntity);
            if (isset($this->response->metadados->urgente)) {
                $tramitacaoDto->setUrgente($this->response->metadados->urgente);
            }
            $tramitacaoDto->setDataHoraRecebimento(new DateTime());
            $tramitacaoDto->setMecanismoRemessa('barramento');
            $tramitacaoEntity = $this->tramitacaoResource->create($tramitacaoDto, $this->transactionId);
        } else {
            /** @var TramitacaoDTO $tramitacaoDto */
            $tramitacaoDto = $this->tramitacaoResource->getDtoForEntity(
                $tramitacaoEntity->getId(),
                TramitacaoDTO::class
            );
            $tramitacaoDto->setDataHoraRecebimento(new DateTime());
            $this->tramitacaoResource->update($tramitacaoEntity->getId(), $tramitacaoDto, $this->transactionId);
        }

        /** @var StatusBarramentoEntity $statusBarramentoEntity */
        $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(
            ['idt' => $this->idt]
        );

        if (!$statusBarramentoEntity) {
            $statusBarramentoDto = new StatusBarramentoDTO();
        } else {
            /** @var StatusBarramentoDTO $statusBarramentoDto */
            $statusBarramentoDto = $this->statusBarramentoResource->getDtoForEntity(
                $statusBarramentoEntity->getId(),
                StatusBarramentoDTO::class
            );
        }

        $statusBarramentoDto->setProcesso($processoEntity);

        $statusBarramentoDto->setTramitacao($tramitacaoEntity);
        $statusBarramentoDto->setIdt($this->idt);
        $statusBarramentoDto->setCodSituacaoTramitacao(
            AbstractBarramentoManager::SIT_META_DADOS_RECEBIDO_DESTINATARIO
        );

        if ($statusBarramentoEntity && $statusBarramentoEntity->getId()) {
            $this->statusBarramentoResource->update(
                $statusBarramentoEntity->getId(),
                $statusBarramentoDto,
                $this->transactionId
            );
        } else {
            $this->statusBarramentoResource->create(
                $statusBarramentoDto,
                $this->transactionId
            );
        }
    }

    /**
     * Este método analisa o possível cenário em que o processo principal oriundo do barramento já está apensado a
     * um processo dentro do SUPP. Este processo "Pai" dentro do SUPP passa a ser o processo principal para que
     * todos os demais processos apensados sejam apesados a este.
     */
    private function atribuiProcessoPrincipal(ProcessoEntity $processoEntity): void
    {
        if ($processoEntity->getId()) {
            /** @var VinculacaoProcessoEntity $vinculacaoProcessoEntity */
            $vinculacaoProcessoEntity = $this->vinculacaoProcessoResource->getRepository()->estaApensada(
                $processoEntity->getId()
            );
            if ($vinculacaoProcessoEntity && $vinculacaoProcessoEntity->getProcesso()) {
                $this->processoPrincipal = $this->processoResource->findOne(
                    $vinculacaoProcessoEntity->getProcesso()->getId()
                );
            }
        }
        if (!$this->processoPrincipal) {
            $this->processoPrincipal = $processoEntity;
        }
    }

    /**
     * Sincroniza processos apensados ao processo principal.
     *
     * @throws AnnotationException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function sincronizaProcessosApensados(
        stdClass $metadadosProcesso,
        PessoaEntity $pessoaRemetente,
        SetorEntity $setorDestinatario,
        string $nre
    ): void {
        if (!isset($metadadosProcesso->processoApensado)) {
            return;
        }

        if (!is_array($metadadosProcesso->processoApensado)) {
            $metadadosProcesso->processoApensado = [$metadadosProcesso->processoApensado];
        }
        foreach ($metadadosProcesso->processoApensado as $processoApensado) {
            $processoEntity = $this->sincronizaProcesso(
                0,
                $processoApensado,
                $pessoaRemetente,
                $setorDestinatario,
                $nre,
                $this->transactionId,
                $this->response
            );

            // Caso a pasta apensada exista no SUPP
            if ($processoEntity->getId()) {
                /** @var VinculacaoProcessoEntity $vinculacaoProcessoEntity */
                $vinculacaoProcessoEntity = $this->vinculacaoProcessoResource->getRepository()->estaApensada(
                    $processoEntity->getId()
                );

                if ($vinculacaoProcessoEntity) {
                    if ($vinculacaoProcessoEntity->getProcesso()->getId() === $this->processoPrincipal->getId()) {
                        return;
                    }
                    $this->logger->critical(
                        "Removendo vinculo do NUP {$processoEntity->getNUPFormatado()} que ".
                        "estava vinculado ao NUP {$vinculacaoProcessoEntity->getProcesso()->getNUP()}, para vincular ".
                        "ao NUP {$this->processoPrincipal->getNUP()}"
                    );

                    $this->vinculacaoProcessoResource->delete($vinculacaoProcessoEntity->getId(), $this->transactionId);
                }
            }

            $vinculacaoDto = new VinculacaoProcesso();
            $vinculacaoDto->setProcesso($this->processoPrincipal);
            $vinculacaoDto->setProcessoVinculado($processoEntity);

            /** @var ModalidadeVinculacaoProcessoEntity $modalidadeVinculacaoProcessoEntity */
            $modalidadeVinculacaoProcessoEntity = $this->modalidadeVinculacaoProcessoResource->findOneBy(
                ['valor' => 'APENSAMENTO']
            );

            $vinculacaoDto->setModalidadeVinculacaoProcesso($modalidadeVinculacaoProcessoEntity);
            $vinculacaoDto->setObservacao('APENSAMENTO OBTIDO POR MEIO DO BARRAMENTO PEN');
            $this->vinculacaoProcessoResource->create($vinculacaoDto, $this->transactionId);
        }
    }

    /**
     * @throws Exception
     */
    private function criaTarefaDistribuidor(ProcessoEntity $processoEntity): void
    {
        $inicioPrazo = new DateTime();
        $finalPrazo = new DateTime();
        $finalPrazo = $finalPrazo->add(new DateInterval('P5D'));

        /** @var SetorEntity $protocolo */
        $protocolo = $this->setorResource->getRepository()
            ->findProtocoloInUnidade($this->setorDestinatario->getId());

        //verifica se o tramite foi para um setor ou unidade
        $setorResponsavel = $protocolo ?? $this->setorDestinatario;

        $tarefaDto = new TarefaDTO();
        $tarefaDto->setProcesso($processoEntity);
        $tarefaDto->setSetorResponsavel($setorResponsavel);
        $tarefaDto->setDistribuicaoAutomatica(true);

        /** @var EspecieTarefaEntity $especieTarefaEntity */
        $especieTarefaEntity = $this->especieTarefaRepository->findByNomeAndGenero(
            'ANALISAR DEMANDAS',
            'ADMINISTRATIVO'
        );
        $tarefaDto->setEspecieTarefa($especieTarefaEntity);
        $tarefaDto->setDataHoraInicioPrazo($inicioPrazo);
        $tarefaDto->setDataHoraFinalPrazo($finalPrazo);

        /* @var TarefaEntity $tarefaEntity */
        $this->tarefaResource->create($tarefaDto, $this->transactionId);
    }
}
