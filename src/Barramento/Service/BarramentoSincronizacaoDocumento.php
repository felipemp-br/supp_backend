<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use ReflectionException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFaseResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeMeioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoProtocoloResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase as ModalidadeFaseEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio as ModalidadeMeioEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\NUP\NUPProviderManager;
use SuppCore\AdministrativoBackend\Repository\AfastamentoRepository;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\EspecieTarefaRepository;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos diversos de documento avulsos entre o SUPP e o Barramento.
 */
class BarramentoSincronizacaoDocumento
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
     * Service para tratamento de transição de objetos.
     */
    private TransicaoResource $transicaoResource;

    /**
     * Service utilizada para criar e sincronizar interessados.
     */
    private BarramentoInteressadoManager $interessadoManager;

    /**
     * Response obtido por meio do request enviado ao Soap
     * Será utilizado para reuso dentro dos métodos das classes herdadas.
     */
    private stdClass $response;

    /**
     * Setor destinatário dentro do SUPP.
     */
    private SetorEntity $setorDestinatario;

    private OrigemDadosResource $origemDadosResource;

    private ProcessoResource $processoResource;

    private ClassificacaoResource $classificacaoResource;

    private EspecieProcessoResource $especieProcessoResource;

    private ModalidadeMeioResource $modalidadeMeioResource;

    private ModalidadeFaseResource $modalidadeFaseResource;

    private VolumeResource $volumeResource;

    private NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource;

    private TipoDocumentoResource $tipoDocumentoResource;

    private TipoDocumentoRepository $tipoDocumentoRepository;

    private DocumentoResource $documentoResource;

    private JuntadaResource $juntadaResource;

    private DocumentoAvulsoResource $documentoAvulsoResource;

    private SetorResource $setorResource;

    private TarefaResource $tarefaResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private EspecieTarefaRepository $especieTarefaRepository;

    private AfastamentoRepository $afastamentoRepository;

    private EspecieProcessoRepository $especieProcessoRepository;

    private TransactionManager $transactionManager;

    private NUPProviderManager $nupProviderManager;

    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        TransicaoResource $transicaoResource,
        BarramentoInteressadoManager $interessadoManager,
        OrigemDadosResource $origemDadosResource,
        ProcessoResource $processoResource,
        ClassificacaoResource $classificacaoResource,
        EspecieProcessoResource $especieProcessoResource,
        EspecieProcessoRepository $especieProcessoRepository,
        ModalidadeMeioResource $modalidadeMeioResource,
        ModalidadeFaseResource $modalidadeFaseResource,
        VolumeResource $volumeResource,
        NumeroUnicoProtocoloResource $numeroUnicoProtocoloResource,
        TipoDocumentoResource $tipoDocumentoResource,
        TipoDocumentoRepository $tipoDocumentoRepository,
        DocumentoResource $documentoResource,
        JuntadaResource $juntadaResource,
        DocumentoAvulsoResource $documentoAvulsoResource,
        SetorResource $setorResource,
        TarefaResource $tarefaResource,
        EspecieTarefaRepository $especieTarefaRepository,
        AfastamentoRepository $afastamentoRepository,
        StatusBarramentoResource $statusBarramentoResource,
        TransactionManager $transactionManager,
        NUPProviderManager $nupProviderManager,
    ) {
        $this->logger = $logger;
        $this->mapTipoDocumento =
            $parameterBag->get('integracao_barramento')['mapeamentos']['tipo_documento_barramento'];
        $this->transicaoResource = $transicaoResource;
        $this->interessadoManager = $interessadoManager;
        $this->origemDadosResource = $origemDadosResource;
        $this->processoResource = $processoResource;
        $this->classificacaoResource = $classificacaoResource;
        $this->especieProcessoResource = $especieProcessoResource;
        $this->modalidadeMeioResource = $modalidadeMeioResource;
        $this->modalidadeFaseResource = $modalidadeFaseResource;
        $this->volumeResource = $volumeResource;
        $this->numeroUnicoProtocoloResource = $numeroUnicoProtocoloResource;
        $this->tipoDocumentoResource = $tipoDocumentoResource;
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
        $this->documentoResource = $documentoResource;
        $this->juntadaResource = $juntadaResource;
        $this->documentoAvulsoResource = $documentoAvulsoResource;
        $this->setorResource = $setorResource;
        $this->tarefaResource = $tarefaResource;
        $this->especieTarefaRepository = $especieTarefaRepository;
        $this->afastamentoRepository = $afastamentoRepository;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->especieProcessoRepository = $especieProcessoRepository;
        $this->transactionManager = $transactionManager;
        $this->nupProviderManager = $nupProviderManager;
    }

    /**
     * Sincroniza uma tramitação de documento avulso recebida do barramento criando objetos
     * no sapiens quando não existir.
     *
     * @throws Exception
     */
    public function sincronizaDocumentoAvulso(
        int $idt,
        stdClass $metadadosDocumento,
        PessoaEntity $pessoaRemetente,
        SetorEntity $setorDestinatario,
        string $nre,
        string $transactionId,
        stdClass $response
    ): ProcessoEntity {
        $this->idt = $idt;
        $this->response = $response;
        $this->transactionId = $transactionId;

        /* @var SetorEntity $setorDestinatario */
        $this->setorDestinatario = $setorDestinatario;

        try {
            // Se existir protocolo relacionado, cria o documento;
            $protocolosRelacionados = $this->response->metadados->documento->protocolo;
            if (!is_array($protocolosRelacionados)) {
                $protocolosRelacionados = [$protocolosRelacionados];
            }

            /*
             * Analisa os protocolos relacionados
             */
            foreach ($protocolosRelacionados as $protocoloRelacionado) {
                /*
                 * O protocolo relacionado será desmembrado e assim vamos obter o Id do Doc Avulso caso sua origem
                 * seja o Supp: numeroUnicoProtocolo_documentoAvulsoId
                 */
                $protocoloRelacionado = explode('_', $protocoloRelacionado);
                if (2 == count($protocoloRelacionado)) {
                    $documentoAvulsoId = $protocoloRelacionado[1];
                    $documentoAvulso = $this->documentoAvulsoResource->findOne((int) $documentoAvulsoId);
                    $processoEntity = $documentoAvulso->getProcesso();
                    $documentoEntity = $this->criaDocumento($processoEntity, $metadadosDocumento, $nre);
                    break;
                } else {
                    $protocoloSemFormatacao = preg_replace(
                        '/\D/',
                        '',
                        $this->response->metadados->documento->protocolo
                    );
                    /** @var Processo $processo */
                    $processoEntity = $this->processoResource->findOneBy(['NUP' => $protocoloSemFormatacao]);

                    if (!$processoEntity) {
                        $processoEntity = $this->criaProcesso(
                            $this->response->metadados->documento,
                            $pessoaRemetente,
                            $nre
                        );
                    }
                    $documentoEntity = $this->criaDocumento($processoEntity, $metadadosDocumento, $nre);
                    $this->criaJuntada(
                        $processoEntity,
                        $documentoEntity,
                        $this->response->metadados->documento,
                        $nre
                    );

                    break;
                }
            }

            $this->sincDocAvulso($documentoEntity);

            $this->interessadoManager->sincronizaInteressados(
                $metadadosDocumento,
                $processoEntity,
                $this->transactionId,
                $nre
            );

            return $processoEntity;
        } catch (Exception $e) {
            $this->logger->critical("Erro: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Cria pasta a partir dos objetos gerados por meio dos metadados rebidos pelo barramento.
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function criaProcesso(
        stdClass $metadadosDocumento,
        PessoaEntity $pessoaRemetente,
        string $nre
    ): ProcessoEntity {
        // $processoDto = $this->processoResource->getDtoClass();
        $processoDto = new ProcessoDTO();

        $processoDto->setUnidadeArquivistica(Processo::UA_DOCUMENTO_AVULSO);
        $processoDto->setTipoProtocolo(ProcessoEntity::TP_INFORMADO);
        $processoDto->setTitulo(strtoupper($metadadosDocumento->descricao));
        $processoDto->setProcedencia($pessoaRemetente);

        $protocoloSetor = $this->setorResource->getRepository()
            ->findProtocoloInUnidade($this->setorDestinatario->getId());

        // verifica se o tramite foi para um setor ou unidade
        $setorAtual = $protocoloSetor ?? $this->setorDestinatario;

        $processoDto->setSetorAtual($setorAtual);

        $processoDto->setNUP(
            preg_replace('/\D/', '', $metadadosDocumento->protocolo)
        );
        $processoDto->setDataHoraAbertura(
            $this->converteDataBarramento($metadadosDocumento->dataHoraDeProducao)
        );

        // verifica Nup valido
        $validarNup = $this->nupProviderManager->getNupProvider(
            $processoDto
        )->validarNumeroUnicoProtocolo($processoDto);

        // caso numero nup for invalido
        if (!$validarNup) {
            $processoDto->setTipoProtocolo(ProcessoEntity::TP_NOVO);
            $processoDto->setOutroNumero($metadadosDocumento->protocolo);
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
     * Sincroniza o Documento Avulso.
     *
     * @throws ReflectionException
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function sincDocAvulso(DocumentoEntity $documentoEntity): void
    {
        if (isset($this->response->metadados->documento->protocolo)) {
            $protocolosRelacionados = $this->response->metadados->documento->protocolo;

            if (!is_array($protocolosRelacionados)) {
                $protocolosRelacionados = [$protocolosRelacionados];
            }

            /*
             * Analisa os protocolos relacionados
             */
            foreach ($protocolosRelacionados as $protocoloRelacionado) {
                /*
                 * O protocolo relacionado será desmembrado e assim vamos obter o Id do Doc Avulso caso sua origem
                 * seja o Supp: numeroUnicoProtocolo_documentoAvulsoId
                 */
                $protocoloRelacionado = explode('_', $protocoloRelacionado);
                if (2 == count($protocoloRelacionado)) {
                    $documentoAvulsoId = $protocoloRelacionado[1];

                    $this->logger->info("Documento Avulso Id {$documentoAvulsoId} extraída do protocolo.");

                    /** @var DocumentoAvulsoDTO $documentoAvulsoDto */
                    $documentoAvulsoDto = $this->documentoAvulsoResource->getDtoForEntity(
                        (int) $documentoAvulsoId,
                        DocumentoAvulsoDTO::class
                    );

                    if ($documentoAvulsoDto) {
                        $documentoAvulsoDto->setDocumentoResposta($documentoEntity);
                        $this->documentoAvulsoResource->responder(
                            (int) $documentoAvulsoId,
                            $documentoAvulsoDto,
                            $this->transactionId
                        );

                        $this->logger->info('Documento Avulso localizado. O documento resposta foi inserido.');
                    } else {
                        $this->logger->critical("Documento Avulso Id {$documentoAvulsoId} não localizado.");
                    }
                }
            }
        } else {
            $msg = 'Não há protocolos anteriores relacionados. O documento avulso trata-se de um documento remessa.';
            $this->logger->info($msg);
            $this->criaTarefaDistribuidor($documentoEntity);
        }
    }

    /**
     * Cria tarefa para distribuidor do setor destino.
     *
     * @throws Exception
     */
    private function criaTarefaDistribuidor(DocumentoEntity $documentoEntity): void
    {
        $inicioPrazo = new DateTime();
        $finalPrazo = new DateTime();
        $finalPrazo = $finalPrazo->add(new DateInterval('P5D'));

        /** @var SetorEntity $protocolo */
        $protocolo = $this->setorResource->getRepository()
            ->findProtocoloInUnidade($this->setorDestinatario->getId());

        // verifica se o tramite foi para um setor ou unidade
        $setorResponsavel = $protocolo ?? $this->setorDestinatario;

        $tarefaDto = new TarefaDTO();
        $tarefaDto->setProcesso($documentoEntity->getProcessoOrigem());
        $tarefaDto->setSetorResponsavel($setorResponsavel);

        /** @var EspecieTarefaEntity $especieTarefaEntity */
        $especieTarefaEntity = $this->especieTarefaRepository
            ->findByNomeAndGenero('ANALISAR DEMANDAS', 'ADMINISTRATIVO');
        $tarefaDto->setEspecieTarefa($especieTarefaEntity);
        $tarefaDto->setDataHoraInicioPrazo($inicioPrazo);
        $tarefaDto->setDataHoraFinalPrazo($finalPrazo);

        try {
            $tarefaDto->setProcesso($documentoEntity->getProcessoOrigem());
            /* @var TarefaEntity $tarefaEntity */
            $this->tarefaResource->create($tarefaDto, $this->transactionId);

            $this->logger->info('Tarefa criada e distribuída para o protocolo.');
        } catch (Exception $e) {
            $msg = 'A unidade de destino não possui usuários disponíveis no '.
                $documentoEntity->getProcessoOrigem()->getSetorAtual()->getNome().' para receber o documento avulso!';
            $this->logger->critical($msg);
            throw $e;
        }
    }

    /**
     * Cria um novo documento a partir dos objetos gerados por meio dos metadados rebidos pelo barramento.
     *
     * @throws Exception
     */
    private function criaDocumento(
        ProcessoEntity $processoEntity,
        stdClass $documentoTramitacao,
        string $nre
    ): DocumentoEntity {
        if (isset($this->mapTipoDocumento[$documentoTramitacao->especie->codigo])) {
            $tipoDocumentoEntity = $this->tipoDocumentoResource->findOneBy(
                ['nome' => $this->upperUtf8($this->mapTipoDocumento[$documentoTramitacao->especie->codigo])]
            );
        } else {
            $tipoDocumentoEntity = $this->tipoDocumentoResource->findOneBy(
                ['nome' => $this->upperUtf8($documentoTramitacao->especie->nomeNoProdutor)]
            );
        }

        if (!$tipoDocumentoEntity) {
            $tipoDocumentoEntity = $this->tipoDocumentoRepository->findByNomeAndEspecie('OUTROS', 'ADMINISTRATIVO');
            $this->logger->info(
                'Tipo de Documento não encontrado: '.$documentoTramitacao->especie->nomeNoProdutor
            );
        }

        $documentoDto = new DocumentoDTO();
        $documentoDto->setProcessoOrigem($processoEntity);
        $documentoDto->setTipoDocumento($tipoDocumentoEntity);
        $documentoDto->setDataHoraProducao($this->converteDataBarramento($documentoTramitacao->dataHoraDeProducao));

        if ('OUTROS' == $tipoDocumentoEntity->getNome()) {
            $documentoDto->setDescricaoOutros($this->upperUtf8($documentoTramitacao->especie->nomeNoProdutor));
        }

        $documentoDto->setAutor($documentoTramitacao->produtor->nome);
        $origemDadosDTO = $this->origemDadosFactory();
        $origemDadosDTO->setIdExterno($nre);
        $origemDadosDTO->setStatus(AbstractBarramentoManager::EM_SINCRONIZACAO);
        $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTO, $this->transactionId);

        $documentoDto->setOrigemDados($origemDadosEntity);

        /*        $numeracaoSequencial = $this->juntadaResource->getRepository()
        ->findMaxNumeracaoSequencialByProcessoId(
                        $processoEntity->getId()
                ) + 1;*/

        $documentoEntity = $this->documentoResource->create($documentoDto, $this->transactionId);

        return $documentoEntity;
    }

    /**
     * Cria uma juntada.
     *
     * @throws Exception
     */
    private function criaJuntada(
        ProcessoEntity $processoEntity,
        DocumentoEntity $documentoEntity,
        stdClass $documentoTramitacao,
        string $nre
    ): Juntada {
        $juntadaDTO = new JuntadaDTO();
        $juntadaDTO->setDocumento($documentoEntity);
        $juntadaDTO->setVinculada(false);
        $juntadaDTO->setDescricao('DOCUMENTO RECEBIDO VIA INTEGRAÇÃO COM O BARRAMENTO');

        $volumeProcesso = (new ArrayCollection($this->transactionManager
            ->getScheduledEntities(Volume::class, $this->transactionId)))
            ->filter(fn (Volume $v) => $v->getProcesso()->getUuid() === $processoEntity->getUuid())
            ->first();
        $juntadaDTO->setVolume($volumeProcesso);

        $origemJuntadaDTO = $this->origemDadosFactory();
        $origemJuntadaDTO->setIdExterno($nre);
        $origemJuntadaDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosJuntadaEntity = $this->origemDadosResource->create($origemJuntadaDTO, $this->transactionId);

        $juntadaDTO->setOrigemDados($origemDadosJuntadaEntity);

        // Verifica se o documento foi cancelado
        if (isset($documentoTramitacao->retirado) && true == $documentoTramitacao->retirado) {
            $juntadaDTO->setAtivo(false);
        }

        return $this->juntadaResource->create($juntadaDTO, $this->transactionId);
    }
}
