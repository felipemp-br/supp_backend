<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoResource;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as StatusBarramentoEntity;
use SuppCore\AdministrativoBackend\Entity\Transicao as TransicaoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento as VinculacaoDocumentoEntity;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por converter comunicação em documento avulso e enviar ao barramento.
 */
class BarramentoEnviaDocumentoAvulso extends AbstractBarramentoManager
{
    private string $transactionId;

    private BarramentoEnvioManager $barramentoEnvioManager;

    private DocumentoAvulsoResource $documentoAvulsoResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private TransicaoResource $transicaoResource;

    protected array $config;

    /**
     * BarramentoEnviaDocumentoAvulso constructor.
     * @param BarramentoLogger $logger
     * @param ParameterBagInterface $parameterBag
     * @param BarramentoClient $barramentoClient
     * @param ProcessoResource $processoResource
     * @param OrigemDadosResource $origemDadosResource
     * @param BarramentoEnvioManager $barramentoEnvioManager
     * @param DocumentoAvulsoResource $documentoAvulsoResource
     * @param TransicaoResource $transicaoResource
     * @param StatusBarramentoResource $statusBarramentoResource
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        BarramentoEnvioManager $barramentoEnvioManager,
        DocumentoAvulsoResource $documentoAvulsoResource,
        TransicaoResource $transicaoResource,
        StatusBarramentoResource $statusBarramentoResource
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->logger = $logger;
        $this->client = $barramentoClient;
        $this->barramentoEnvioManager = $barramentoEnvioManager;
        $this->documentoAvulsoResource = $documentoAvulsoResource;
        $this->transicaoResource = $transicaoResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
    }

    /**
     * Inicia um trâmite de documento avulso. Os metadados do documento e seus componentes digitais são
     * informados nesse passo.
     *
     * @param int $transactionId
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function enviarDocumento(
        int $identificacaoDoRepositorioDeEstruturasRemetente,
        int $numeroDeIdentificacaoDaEstruturaRemetente,
        int $identificacaoDoRepositorioDeEstruturasDestinatario,
        int $numeroDeIdentificacaoDaEstruturaDestinatario,
        int $documentoAvulsoId,
        string $transactionId
    ) {
        $this->transactionId = $transactionId;

        /** @var DocumentoAvulsoEntity $documentoAvulsoEntity */
        $documentoAvulsoEntity = $this->documentoAvulsoResource->findOne($documentoAvulsoId);

        if (!$documentoAvulsoEntity) {
            throw new Exception('Documento Avulso não localizado.');
        }

        if (!$documentoAvulsoEntity->getProcesso()) {
            throw new Exception('Processo não localizado.');
        }

        $novoTramiteDeDocumento = new stdClass();
        $setorDestinatario = new stdClass();
        $remetente = new stdClass();
        $cabecalho = new stdClass();
        $params = new stdClass();

        $remetente->identificacaoDoRepositorioDeEstruturas = $identificacaoDoRepositorioDeEstruturasRemetente;
        $remetente->numeroDeIdentificacaoDaEstrutura = $numeroDeIdentificacaoDaEstruturaRemetente;
        $setorDestinatario->identificacaoDoRepositorioDeEstruturas =
            $identificacaoDoRepositorioDeEstruturasDestinatario;
        $setorDestinatario->numeroDeIdentificacaoDaEstrutura = $numeroDeIdentificacaoDaEstruturaDestinatario;

        $cabecalho->remetente = $remetente;
        $cabecalho->destinatario = [$setorDestinatario];
        if (null !== $documentoAvulsoEntity->getUrgente()) {
            $cabecalho->urgente = $documentoAvulsoEntity->getUrgente();
        }
        /*if ($documentoAvulsoEntity->getProcesso()->getOrigemDados() &&
            'BARRAMENTO_PEN' == $documentoAvulsoEntity->getProcesso()->getOrigemDados()->getFonteDados()
        ) {
            $cabecalho->NRE = $documentoAvulsoEntity->getProcesso()->getOrigemDados()->getIdExterno();
        }*/

        $novoTramiteDeDocumento->cabecalho = $cabecalho;

        /** @var stdClass $documentoAvulsoBarramento */
        $documentoAvulsoBarramento = $this->documentoAvulsoEntityToBarramento($documentoAvulsoEntity);

        if (!$documentoAvulsoBarramento) {
            return false;
        }

        $novoTramiteDeDocumento->documento = $documentoAvulsoBarramento;

        $params->novoTramiteDeDocumento = $novoTramiteDeDocumento;

        $responseClient = $this->client->enviarDocumento($params);

        if (!$responseClient) {
            throw new Exception($this->client->getMensagemErro());
        }

        $this->atualizaTicketDocumentoAvulso($responseClient, $documentoAvulsoEntity);

        return $responseClient;
    }

    /**
     * Converte o objeto Documento Avulso Entity do SUPP em Documento Avulso no formato do barramento.
     *
     * @throws Exception Processo em transição de arquivamento
     */
    private function documentoAvulsoEntityToBarramento(DocumentoAvulsoEntity $documentoAvulsoEntity): stdClass
    {
        /** @var ProcessoEntity $processoEntity */
        $processoEntity = $documentoAvulsoEntity->getProcesso();

        /** @var TransicaoEntity $transicaoEntity */
        $transicaoEntity = $this->transicaoResource->getRepository()->findUltimaCriadaByProcessoId(
            $processoEntity->getId()
        );

        if ($transicaoEntity) {
            $modalidade = $transicaoEntity->getModalidadeTransicao()->getValor();
            if ('TRANSFERENCIA' == $modalidade || 'RECOLHIMENTO' == $modalidade || 'ELIMINAÇÃO' == $modalidade) {
                throw new Exception('Este processo possui transição 
                de arquivamento e por isso não pode ser '.'tramitado.');
            }
        }

        return $this->getDocumentoAvulso($documentoAvulsoEntity);
    }

    /**
     * Transforma DocumentoAvulsoEntity do SUPP em Documento Avulso no formato do barramento.
     *
     * @throws Exception este processo está apensado e não pode ser enviado
     */
    private function getDocumentoAvulso(DocumentoAvulsoEntity $documentoAvulsoEntity): stdClass
    {
        /** @var ProcessoEntity $processo */
        $processo = $documentoAvulsoEntity->getProcesso();

        $documentoAvulsoBarramento = new stdClass();
        $produtor = new stdClass();
        $especie = new stdClass();

        $documentoAvulsoBarramento->protocolo = $processo->getNUP().'_'.$documentoAvulsoEntity->getId();
        $documentoAvulsoBarramento->nivelDeSigilo = '1';

        $produtor->nome = $documentoAvulsoEntity->getCriadoPor()->getNome();
        $documentoAvulsoBarramento->produtor = $produtor;

        $documentoAvulsoBarramento->descricao = $documentoAvulsoEntity->getEspecieDocumentoAvulso()->getNome();

        if (null == $documentoAvulsoEntity->getProcesso()->getProcedencia()->getOrigemDados()) {
            $dataHoraDeProducao = $processo->getCriadoEm()->format('Y-m-d\TH:i:s.000P');
        } else {
            $dataHoraDeProducao = $processo->getDataHoraAbertura()->format('Y-m-d\TH:i:s.000P');
        }

        $documentoAvulsoBarramento->dataHoraDeProducao = $dataHoraDeProducao;
        $documentoAvulsoBarramento->dataHoraDeRegistro = $processo->getCriadoEm()->format('Y-m-d\TH:i:s.000P');

        /** @var DocumentoEntity $documentoRemessaEntity */
        $documentoRemessaEntity = $documentoAvulsoEntity->getDocumentoRemessa();

        $nomeTipoDocumento = $documentoRemessaEntity->getTipoDocumento()->getNome();
        $mapeamentoTipoDocumento = $this->mapeamentos['tipo_documento_barramento'];

        $indiceMapeamento = array_search(
            mb_strtoupper($nomeTipoDocumento),
            array_map('mb_strtoupper', $mapeamentoTipoDocumento)
        );

        if ($indiceMapeamento) {
            $especie->codigo = $indiceMapeamento;
            $especie->nomeNoProdutor = $mapeamentoTipoDocumento[$indiceMapeamento];
        } else {
            $especie->codigo = 999;
            $especie->nomeNoProdutor = $nomeTipoDocumento;
            $this->logger->critical("O tipo de documento [$nomeTipoDocumento] não está mapeado no barramento.".
                'O SPE destino poderá recusar o trâmite.');
        }

        $documentoAvulsoBarramento->especie = $especie;

        $identificacao = $this->barramentoEnvioManager->getIdentificacacaoDocumento($documentoRemessaEntity);

        if ($identificacao) {
            $documentoAvulsoBarramento->identificacao = $identificacao;
        }

        $documentoAvulsoBarramento->componenteDigital =
            $this->barramentoEnvioManager->getComponentesDigitais($documentoRemessaEntity, $this->transactionId);

        /** @var VinculacaoDocumentoEntity[] $vinculacoes */
        $vinculacoes = $documentoRemessaEntity->getVinculacoesDocumentos();

        foreach ($vinculacoes as $vinculacao) {
            $componentesDaVinculacao = $this->barramentoEnvioManager->getComponentesDigitais(
                $vinculacao->getDocumentoVinculado(),
                $this->transactionId
            );

            foreach ($componentesDaVinculacao as $componente) {
                $componente->ordem = count($documentoAvulsoBarramento->componenteDigital) + 1;
                $documentoAvulsoBarramento->componenteDigital[] = $componente;
            }
        }

        $documentoAvulsoBarramento->interessado = $this->barramentoEnvioManager->getInteressados($processo);

        return $documentoAvulsoBarramento;
    }

    /**
     * Insere número do ticket do trâmite criado no objeto Documento Avulso.
     */
    private function atualizaTicketDocumentoAvulso(
        stdClass $responseClient,
        DocumentoAvulsoEntity $documentoAvulsoEntity
    ): void {
        try {
            $idt = $responseClient->dadosTramiteDeDocumentoCriado->tramite->IDT;
            $idtComponenteDigital =
                $responseClient->dadosTramiteDeDocumentoCriado->ticketParaEnvioDeComponentesDigitais;

            /** @var ProcessoEntity $processoEntity */
            $processoEntity = $documentoAvulsoEntity->getProcesso();

            /** @var StatusBarramentoEntity $statusBarramentoEntity */
            $statusBarramentoEntity = $this->statusBarramentoResource->findOneBy(
                ['idt' => $idt]
            );

            if ($statusBarramentoEntity) {
                $statusBarramentoDto = $this->statusBarramentoResource
                    ->getDtoForEntity(
                        $statusBarramentoEntity->getId(),
                        StatusBarramentoDTO::class
                    );

                $statusBarramentoDto->setIdt($idt);
                $statusBarramentoDto->setIdtComponenteDigital($idtComponenteDigital);
                $statusBarramentoDto->setCodSituacaoTramitacao(
                    AbstractBarramentoManager::SIT_DOC_PROC_RECEBIDO_BARRAMENTO
                );

                $statusBarramentoDto->setDocumentoAvulso($documentoAvulsoEntity);

                $this->statusBarramentoResource->update(
                    $statusBarramentoEntity->getId(),
                    $statusBarramentoDto,
                    $this->transactionId
                );
            } else {
                $statusBarramentoDto = new StatusBarramentoDTO();
                $statusBarramentoDto->setIdt($idt);
                $statusBarramentoDto->setIdtComponenteDigital($idtComponenteDigital);
                $statusBarramentoDto->setCodSituacaoTramitacao(
                    AbstractBarramentoManager::SIT_DOC_PROC_RECEBIDO_BARRAMENTO
                );

                $statusBarramentoDto->setDocumentoAvulso($documentoAvulsoEntity);

                $statusBarramentoEntity = $this->statusBarramentoResource
                    ->create($statusBarramentoDto, $this->transactionId);
            }
        } catch (Exception $e) {
            $this->logger->critical("Ocorreu um erro ao tentar salvar o número do ticket [$idt] ".
                "no objeto Comunicacao Id [$documentoAvulsoEntity->getId()]");
        }
    }
}
