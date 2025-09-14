<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource as TramitacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource as VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Transicao;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository as JuntadaRepository;
use SuppCore\AdministrativoBackend\Repository\StatusBarramentoRepository;
use SuppCore\AdministrativoBackend\Repository\TransicaoRepository as TransicaoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository as VinculacaoProcessoRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Classe responsável por converter tramitação em processo e enviar ao barramento.
 */
class BarramentoEnviaProcesso extends AbstractBarramentoManager
{
    /**
     * Serviço utilizado para obter dados em comum entre processo e documento avulso para envio ao barramento.
     */
    private BarramentoEnvioManager $envioManager;

    private JuntadaRepository $juntadaRepository;

    private TramitacaoResource $tramitacaoResource;

    private VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private TransicaoRepository $transicaoRepository;

    private VinculacaoProcessoResource $vinculacaoProcessoResource;

    private ProcessoResource $processoResource;

    private OrigemDadosResource $origemDadosResource;

    private StatusBarramentoResource $statusBarramentoResource;

    private StatusBarramentoRepository $statusBarramentoRepository;

    protected array $config;

    private string $transactionId;

    private string $nre;

    /**
     * BarramentoEnviaProcesso constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        TransicaoRepository $transicaoRepository,
        TramitacaoResource $tramitacaoResource,
        VinculacaoProcessoRepository $vinculacaoProcessoRepository,
        VinculacaoProcessoResource $vinculacaoProcessoResource,
        JuntadaRepository $juntadaRepository,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        BarramentoEnvioManager $envioManager,
        StatusBarramentoResource $statusBarramentoResource,
        StatusBarramentoRepository $statusBarramentoRepository
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        parent::__construct(
            $logger,
            $this->config,
            $barramentoClient,
            $processoResource,
            $origemDadosResource
        );
        $this->transicaoRepository = $transicaoRepository;
        $this->tramitacaoResource = $tramitacaoResource;
        $this->vinculacaoProcessoRepository = $vinculacaoProcessoRepository;
        $this->vinculacaoProcessoResource = $vinculacaoProcessoResource;
        $this->juntadaRepository = $juntadaRepository;
        $this->processoResource = $processoResource;
        $this->origemDadosResource = $origemDadosResource;
        $this->statusBarramentoResource = $statusBarramentoResource;
        $this->envioManager = $envioManager;
        $this->statusBarramentoRepository = $statusBarramentoRepository;
    }

    /**
     * Utilizado para armazenar processo principal do Supp,
     * permitindo a recursividade de criação dos processo apensados ao principal.
     */
    private ?Processo $processoPrincipal = null;

    /**
     * Inicia um trâmite de processo ao barramento com seus respectivos documentos e componentes digitais.
     *
     * @param $identificacaoDoRepositorioDeEstruturasRemetente
     * @param $numeroDeIdentificacaoDaEstruturaRemetente
     * @param $identificacaoDoRepositorioDeEstruturasDestinatario
     * @param $numeroDeIdentificacaoDaEstruturaDestinatario
     * @param $tramitacaoUuid
     * @param $transactionId
     *
     * @return bool|mixed|string
     *
     * @throws Exception
     */
    public function enviarProcesso(
        $identificacaoDoRepositorioDeEstruturasRemetente,
        $numeroDeIdentificacaoDaEstruturaRemetente,
        $identificacaoDoRepositorioDeEstruturasDestinatario,
        $numeroDeIdentificacaoDaEstruturaDestinatario,
        $tramitacaoUuid,
        $transactionId
    ): mixed {
        $this->transactionId = $transactionId;
        $this->nre = '';
        $novoTramiteDeProcesso = new stdClass();
        $remetente = new stdClass();
        $remetente
            ->identificacaoDoRepositorioDeEstruturas = $identificacaoDoRepositorioDeEstruturasRemetente;
        $remetente
            ->numeroDeIdentificacaoDaEstrutura = $numeroDeIdentificacaoDaEstruturaRemetente;
        $setorDestinatario = new stdClass();
        $setorDestinatario
            ->identificacaoDoRepositorioDeEstruturas = $identificacaoDoRepositorioDeEstruturasDestinatario;
        $setorDestinatario
            ->numeroDeIdentificacaoDaEstrutura = $numeroDeIdentificacaoDaEstruturaDestinatario;

        $cabecalho = new stdClass();
        $cabecalho->remetente = $remetente;
        $cabecalho->destinatario = $setorDestinatario;
        /** @var TramitacaoEntity $tramitacao */
        $tramitacao = $this->tramitacaoResource->findOneBy(['uuid' => $tramitacaoUuid]);
        $cabecalho->urgente = (bool)$tramitacao->getUrgente();

        if ($tramitacao->getProcesso()->getOrigemDados() &&
            'BARRAMENTO_PEN' == $tramitacao->getProcesso()->getOrigemDados()->getFonteDados()) {
            $cabecalho->NRE = $tramitacao->getProcesso()->getOrigemDados()->getIdExterno();
        }

        $processo = $this->tramitacaoToProcesso($tramitacao);
        if (!$processo) {
            return false;
        }

        if (!$tramitacao->getProcesso()->getOrigemDados() &&
            '' !== $this->nre) {
            $cabecalho->NRE = $this->nre;
        }

        // por ultimo verifica se houve envio anterior para pegar o nre
        if (!isset($cabecalho->NRE)) {
            $statusBarramentoEntity = $this->statusBarramentoRepository
                ->findUltimoTramiteEnvio($tramitacao->getProcesso());

            if ($statusBarramentoEntity) {
                $responseConsultaIDT = $this->client->consultarTramites($statusBarramentoEntity->getIdt());
                $cabecalho->NRE = $responseConsultaIDT->tramitesEncontrados->tramite->NRE;
            }
        }

        $novoTramiteDeProcesso->cabecalho = $cabecalho;
        $novoTramiteDeProcesso->processo = $processo;

        $params = new stdClass();
        $params->novoTramiteDeProcesso = $novoTramiteDeProcesso;
        $responseClient = $this->client->enviarProcesso($params);
        if (!$responseClient) {
            throw new Exception($this->client->getMensagemErro());
        }

        $this->atualizaTicketTramitacao($responseClient, $tramitacao);

        return $responseClient;
    }

    /**
     * Converte o objeto tramitação do Supp em processo no formato do barramento.
     *
     * @throws Exception Processo não localizada
     * @throws Exception Processo em transição de arquivamento
     * @throws Exception Tramitação não localizada
     */
    private function tramitacaoToProcesso(TramitacaoEntity $tramitacao): stdClass
    {
        if (!$tramitacao) {
            throw new Exception('Tramitação não localizada.');
        }

        $processo = $tramitacao->getProcesso();

        if (!$processo) {
            throw new Exception('Processo não localizado.');
        }
        /** @var Transicao $transicao */
        $transicao = $this->transicaoRepository->findUltimaTransicaoByProcesso($processo);
        if ($transicao) {
            $modalidade = $transicao->getModalidadeTransicao()->getValor();
            if ('TRANSFERENCIA' == $modalidade || 'RECOLHIMENTO' == $modalidade || 'ELIMINAÇÃO' == $modalidade) {
                throw new Exception('Este processo possui 
                transição de arquivamento e por isso não pode ser '.'tramitado.');
            }
        }
        // Guarda processo principal do trâmite
        if (null == $this->processoPrincipal) {
            $this->processoPrincipal = $processo;
        }
        $processo = $this->getProcesso($processo);

        return $processo;
    }

    /**
     * Transforma processo(s) do Supp em processo(s) no formato do barramento.
     *
     *@throws Exception este processo está apensado e não pode ser enviado
     */
    private function getProcesso(Processo $processoSupp): stdClass
    {
        $processo = new stdClass();
        if ($processoSupp->getOrigemDados() &&
            $processoSupp->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN' &&
            $processoSupp->getOutroNumero()) {
            $processo->protocolo = $processoSupp->getOutroNumero();
        } else {
            $processo->protocolo = $processoSupp->getNUPFormatado();
        }
        $nivelSigilo = $processoSupp->getAcessoRestrito() ? '2' : '1';

        $processo->nivelDeSigilo = $nivelSigilo;

        $produtor = new stdClass();
        $produtor->nome = 'SUPER';
        if ($processoSupp->getCriadoPor()) {
            $produtor->nome = $processoSupp->getCriadoPor()->getNome();
        }

        $processo->produtor = $produtor;
        $processo->descricao = $processoSupp->getTitulo();

        //Se origem dados for igual a NULL, usar a mesma data e hora
        if (null == $processoSupp->getProcedencia()->getOrigemDados()) {
            $dataHoraDeProducao = $processoSupp->getCriadoEm()->format('Y-m-d\TH:i:s.000P');
        } else {
            $dataHoraDeProducao = $processoSupp->getDataHoraAbertura()->format('Y-m-d\TH:i:s.000P');
        }

        $processo->dataHoraDeProducao = $dataHoraDeProducao;
        $processo->dataHoraDeRegistro = $processoSupp->getCriadoEm()->format('Y-m-d\TH:i:s.000P');

        if ($processoSupp->getDataHoraEncerramento()) {
            $processo->dataHoraDeEncerramento = $processoSupp->getDataHoraEncerramento()->format('Y-m-d\TH:i:s.000P');
        }

        // Impede o envio somente quando a processo estiver apensada e for a processo principal
        if ($processoSupp->getId() == $this->processoPrincipal->getId()) {
            $processoApensada = $this->vinculacaoProcessoRepository->estaApensada($processoSupp->getId());
            if ($processoApensada) {
                throw new Exception('Este processo está apensado e não pode ser enviado.');
            }
        }
        // **DESATIVADO** temporariamente por limitação no SEI
//        if (count($processoSupp->getVinculacoesProcessos()) > 0) {
//            /** @var VinculacaoProcesso $vinculacao */
//            foreach ($processoSupp->getVinculacoesProcessos() as $vinculacao) {
//                if ('APENSAMENTO' == $vinculacao->getModalidadeVinculacaoProcesso()->getValor()) {
//                    $processoApensado = $this->getProcesso($vinculacao->getProcessoVinculado());
//                    $processo->processoApensado = $processoApensado;
//                }
//            }
//        }

        $processo->documento = $this->getDocumentos($processoSupp);
        $processo->interessado = $this->envioManager->getInteressados($processoSupp);

        return $processo;
    }

    //desativado temporariamente por problemas no SEI
//    /**
//     * Retorna juntada de um array de juntadas conforme id do documento informado
//     * @param $documentoId
//     * @param $juntadas
//     * @return Juntada
//     * @throws Exception
//     */
//    private function getJuntadaByDocumentoId($documentoId, $juntadas)
//    {
//        /* @var $juntada Juntada */
//        foreach ($juntadas as $juntada) {
//            if ($juntada->getDocumento()->getId() == $documentoId) {
//                return $juntada;
//            }
//        }
//        throw new Exception('Juntada não localizada.');
//    }

    /**
     * Obtém documento(s) do Supp e transforma em documento(s) no formato do barramento
     * juntamente com seus componentes digitais.
     */
    private function getDocumentos(Processo $processo): array
    {
        $ordem_processo_existente = 0;
        $documentosRetorno = [];
        $juntadas = $this->juntadaRepository->findJuntadaByProcesso($processo);
        /* @var Juntada $juntada */
        foreach ($juntadas as $juntada) {
            //verifica se era processo existente, desconsidera as juntadas antigas
            if ($processo->getOrigemDados()?->getServico() === 'barramento_existente' && !$ordem_processo_existente) {
                if (!$juntada->getOrigemDados()) {
                    continue;
                } else {
                    $ordem_processo_existente++;
                }
            }

            $documento = new stdClass();
            // retirado
            $documento->retirado = (!$juntada->getAtivo());
            // ordem
            $documento->ordem = $processo->getOrigemDados()?->getServico() === 'barramento_existente' ?
                $ordem_processo_existente :
                $juntada->getNumeracaoSequencial();

            if ($juntada->getDocumento()->getOutroNumero() &&
                $juntada->getDocumento()->getOrigemDados()) {
                // protocoloDoDocumentoAnexado
                $documento->protocoloDoProcessoAnexado = $juntada->getDocumento()->getOutroNumero();
            }

            // **DESATIVADO** temporariamente por problemas no SEI
            // ordemDoDocumentoReferenciado
//            $vinculacoes = $juntada->getDocumento()->getVinculacoesDocumentos();
//            /* @var $vinculacao VinculacaoDocumento */
//            foreach ($vinculacoes as $vinculacao) {
//                $juntadaVinculada = $this->getJuntadaByDocumentoId(
//                    $vinculacao->getDocumentoVinculado()->getId(),
//                    $juntadas
//                );
//
//                // desativado temporariamente por problemas no SEI
//                //$documento->ordemDoDocumentoReferenciado[] = $juntadaVinculada->getNumeracaoSequencial();
//            }

            $nivelSigilo = $juntada->getDocumento()->getAcessoRestrito() ? '2' : '1';
            $documento->nivelDeSigilo = $nivelSigilo;
            // volume
            $documento->volume = $juntada->getVolume()->getNumeracaoSequencial();
            // produtor
            $produtor = new stdClass();
            $produtor->nome = 'SUPER';
            if ($processo->getCriadoPor()) {
                $produtor->nome = $processo->getCriadoPor()->getNome();
            }
            $documento->produtor = $produtor;
            // descricao
            $documento->descricao = $juntada->getDocumento()->getTipoDocumento()->getDescricao();
            // caso tenha nre salva pra usar no cabecalho
            if ($juntada->getDocumento()->getOrigemDados() &&
                $juntada->getDocumento()->getOrigemDados()->getIdExterno()) {
                $this->nre = (string) $juntada->getDocumento()->getOrigemDados()->getIdExterno();
            }
            // Se origem dados for igual a NULL, usar a mesma dataHora
            if (null == $processo->getProcedencia()->getOrigemDados()) {
                $dataHoraDeProducao = $processo->getCriadoEm()->format('Y-m-d\TH:i:s.000P');
            } else {
                $dataHoraDeProducao = $processo->getDataHoraAbertura()->format('Y-m-d\TH:i:s.000P');
            }
            // dataHoraDeProducao
            $documento->dataHoraDeProducao = $dataHoraDeProducao;
            // dataHoraDeRegistro
            $documento->dataHoraDeRegistro = $processo->getCriadoEm()->format('Y-m-d\TH:i:s.000P');
            // especie
            $documento->especie = $this->envioManager->getEspecieDocumento($juntada->getDocumento());
            // identificacao
            if ($this->envioManager->getIdentificacacaoDocumento($juntada->getDocumento())) {
                $documento->identificacao = $this->envioManager->getIdentificacacaoDocumento($juntada->getDocumento());
            }
            // componenteDigital
            $documento->componenteDigital = $this->envioManager
                ->getComponentesDigitais($juntada->getDocumento(), $this->transactionId);
            // historico
            $documentosRetorno[] = $documento;
            $ordem_processo_existente++;
        }

        return $documentosRetorno;
    }

    /**
     * Insere número do ticket do trâmite criado no objeto Tramitacao.
     *
     * @throws Exception
     */
    private function atualizaTicketTramitacao(stdClass $responseClient, TramitacaoEntity $tramitacao): void
    {
        try {
            /*
             * Aqui nasce o IDT
             */
            $idt = $responseClient->dadosTramiteDeProcessoCriado->IDT;
            $idtComponenteDigital = $responseClient->dadosTramiteDeProcessoCriado->ticketParaEnvioDeComponentesDigitais;

            $statusBarramentoDTO = new StatusBarramentoDTO();
            $statusBarramentoDTO->setIdt($idt);
            $statusBarramentoDTO->setIdtComponenteDigital($idtComponenteDigital);
            $statusBarramentoDTO->setTramitacao($tramitacao);
            $statusBarramentoDTO->setCodSituacaoTramitacao(
                AbstractBarramentoManager::SIT_DOC_PROC_RECEBIDO_BARRAMENTO
            );

            $statusBarramentoDTO->setProcesso($tramitacao->getProcesso());

            $this->statusBarramentoResource
                ->create($statusBarramentoDTO, $this->transactionId);
        } catch (Throwable) {
            $this->logger->critical("Ocorreu um erro ao tentar salvar o número do ticket [$idt] ".
                'no objeto Tramitacao Id ['.$tramitacao->getId().']');
        }
    }
}
