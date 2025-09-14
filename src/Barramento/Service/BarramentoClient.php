<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Exception;
use SoapFault;
use stdClass;

/**
 * Service Soap Client o qual faz comunicação direta com o barramento do PEN.
 *
 * @codeCoverageIgnore
 */
class BarramentoClient extends BarramentoSoap
{
    /**
     * Fornece a lista de repositórios de estruturas organizacionais, insumo necessário para que o SPE possa
     * fornecer uma interface para seu usuário procurar o destinatário do trâmite desejado.
     *
     * @param bool $ativo
     *
     * @return mixed
     */
    public function consultarRepositoriosDeEstruturas(bool $ativo = true): mixed
    {
        $params = new stdClass();
        $params->filtroDeConsultaDeRepositoriosDeEstrutura = new stdClass();
        $params->filtroDeConsultaDeRepositoriosDeEstrutura->ativos = $ativo;

        try {
            parent::iniciaRequisicao(__FUNCTION__);
            $consultarRespositoriosResponse = $this->executaBarramento('consultarRepositoriosDeEstruturas', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $consultarRespositoriosResponse;
    }

    /**
     * Fornece, em estrutura de lista, permitindo filtros, informações das estruturas organizacionais de um
     * determinado repositório de estruturas.
     *
     * @param int         $identificacaoRepositorio
     * @param string|null $identificacaoEstrutura
     * @param int|null    $nomeDaEstrutura
     * @param null        $limit
     * @param null        $offset
     *
     * @return mixed
     */
    public function consultarEstruturas(
        int $identificacaoRepositorio,
        int $identificacaoEstrutura = null,
        string $nomeDaEstrutura = null,
        $limit = null,
        $offset = null
    ): mixed {
        $params = new stdClass();
        $params->filtroDeEstruturas = new stdClass();
        $params->filtroDeEstruturas->apenasAtivas = true;
        $params->filtroDeEstruturas->identificacaoDoRepositorioDeEstruturas = $identificacaoRepositorio;

        if ($identificacaoEstrutura) {
            $params->filtroDeEstruturas->numeroDeIdentificacaoDaEstrutura = $identificacaoEstrutura;
        }

        if ($nomeDaEstrutura) {
            $params->filtroDeEstruturas->nome = trim($nomeDaEstrutura);
        }

        if ($offset || $limit) {
            $params->filtroDeEstruturas->paginacao = new stdClass();
            if ($offset) {
                $params->filtroDeEstruturas->paginacao->registroInicial = $offset;
            } else {
                $params->filtroDeEstruturas->paginacao->registroInicial = 0;
            }
        }

        if ($limit) {
            $params->filtroDeEstruturas->paginacao->quantidadeDeRegistros = $limit;
        }

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $consultarEstruturasResponse = $this->executaBarramento('consultarEstruturas', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $consultarEstruturasResponse;
    }

    /**
     * Fornece, em estrutura de árvore, informações das estruturas organizacionais de um repositório de estruturas.
     * A intenção é que este serviço seja utilizado em complemento ao anterior, para fornecer interfaces
     * flexíveis de busca de estruturas.
     *
     * @param int $identificacaoRepositorio
     * @param int $identificacaoEstrutura
     *
     * @return mixed
     */
    public function consultarEstruturasPorEstruturaPai(
        int $identificacaoRepositorio,
        int $identificacaoEstrutura
    ): mixed {
        $params = new stdClass();
        $params->filtroDeEstruturasPorEstruturaPai = new stdClass();
        $params->filtroDeEstruturasPorEstruturaPai->identificacaoDoRepositorioDeEstruturas = $identificacaoRepositorio;
        $params->filtroDeEstruturasPorEstruturaPai->numeroDeIdentificacaoDaEstrutura = $identificacaoEstrutura;
        $params->filtroDeEstruturasPorEstruturaPai->apenasAtivas = true;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $consultarEstruturasPorEstruturaPaiResponse =
                $this->executaBarramento('consultarEstruturasPorEstruturaPai', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $consultarEstruturasPorEstruturaPaiResponse->estruturasEncontradasNoFiltroPorEstruturaPai;
    }

    /**
     * Fornece uma lista com todos os trâmites pendentes que estão aguardando algum tipo de ação do mesmo.
     * Cada trâmite pendente listado nesse serviço indica o tipo de pendência, que por decorrência permite
     * identificar a ação esperada do SPE sobre ele.
     *
     * @return mixed
     */
    public function listarPendencias(): mixed
    {
        $params = new stdClass();
        $params->filtroDePendencias = new stdClass();
        $params->filtroDePendencias->todasAsPendencias = true;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $listarPendenciasResponse = $this->executaBarramento('listarPendencias', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $listarPendenciasResponse;
    }

    /**
     * Fornece informações dos trâmites relacionados ao SPE, como números de identificação, histórico de
     * situações, entre várias informações.
     *
     * @param int|null    $idt
     * @param string|null $protocolo
     * @param int|null    $situacao
     *
     * @return mixed
     */
    public function consultarTramites(int $idt = null, string $protocolo = null, int $situacao = null): mixed
    {
        $params = new stdClass();
        $params->filtroDeConsultaDeTramites = new stdClass();
        $params->filtroDeConsultaDeTramites->IDT = $idt;
        $params->filtroDeConsultaDeTramites->protocolo = $protocolo;

        if ($situacao) {
            $params->filtroDeConsultaDeTramites->situacaoAtual = $situacao;
        }

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $consultarTramitesResponse = $this->executaBarramento('consultarTramites', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $consultarTramitesResponse;
    }

    /**
     * Inicia um trâmite de processo administrativo. Os metadados do processo e de todos os componentes digitais
     * são enviados neste passo. Na resposta, a solução indica quais os componentes digitais que devem ser
     * enviados para que o processo siga para o destinatário (em alguns casos pode ser identificado que o
     * destinatário já possui aquele componente digital, caso em que o mesmo não é solicitado novamente).
     *
     * @return mixed
     */
    public function enviarProcesso(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $enviarProcessoResponse = $this->executaBarramento('enviarProcesso', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $enviarProcessoResponse;
    }

    /**
     * Inicia um trâmite de documento avulso. Os metadados do documento e seus componentes digitais são
     * informados nesse passo.
     *
     * @return mixed
     */
    public function enviarDocumento(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $enviarDocumentoResponse = $this->executaBarramento('enviarDocumento', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $enviarDocumentoResponse;
    }

    /**
     * Efetuar o envio do componente digital (sua representação binária) para a solução. Após o envio de
     * todos os componentes digitais do trâmite, ele segue para o destinatário.
     *
     * @return mixed
     */
    public function enviarComponenteDigital(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $enviarComponenteDigitalResponse = $this->executaBarramento('enviarComponenteDigital', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $enviarComponenteDigitalResponse;
    }

    /**
     * Entregar o recibo ao remetente, e após isso, concluir o trâmite. Após essa chamada o trâmite não representa
     * mais pendência para nenhum SPE.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function receberReciboDeTramite(int $idt): mixed
    {
        $params = new stdClass();
        $params->IDT = $idt;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $receberReciboDeTramiteResponse = $this->executaBarramento('receberReciboDeTramite', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);
            throw $e;
        }

        return $receberReciboDeTramiteResponse;
    }

    /**
     * Cancelar um trâmite que ainda não foi concluído. Este operação pode ser executada enquanto o SPE de
     * destino não enviar o recibo assinado para a solução.
     *
     * @return mixed
     */
    public function cancelarEnvioDeTramite(int $idt): mixed
    {
        $params = new stdClass();
        $params->IDT = $idt;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $cancelarEnvioDeTramiteResponse = $this->executaBarramento('cancelarEnvioDeTramite', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $cancelarEnvioDeTramiteResponse;
    }

    /**
     * Reproduzir o último trâmite para um determinado destinatário. Esse serviço tem o intuito de suprir o caso
     * onde o SPE de destino deseja receber novamente um conteúdo que ele já recebeu anteriormente.
     *
     * @return mixed
     */
    public function reproduzirUltimoTramite(
        string $nre,
        int $identificacaoDoRepositorioDeEstruturas,
        int $numeroDeIdentificacaoDaEstrutura
    ): mixed {
        $params = new stdClass();

        // NRE
        $params->dadosDoTramiteAReproduzir = new stdClass();
        $params->dadosDoTramiteAReproduzir->NRE = $nre;

        // Destinatário
        $params->dadosDoTramiteAReproduzir->destinatario = new stdClass();
        $params->dadosDoTramiteAReproduzir->destinatario
            ->identificacaoDoRepositorioDeEstruturas = $identificacaoDoRepositorioDeEstruturas;
        $params->dadosDoTramiteAReproduzir
            ->destinatario->numeroDeIdentificacaoDaEstrutura = $numeroDeIdentificacaoDaEstrutura;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $reproduzirUltimoTramiteResponse = $this->executaBarramento('reproduzirUltimoTramite', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $reproduzirUltimoTramiteResponse;
    }

    /**
     * Fornece ao SPE os dados do processo ou documento que está sendo tramitado. Depois dessa chamada,
     * o SPE pode efetuar o recebimento dos componentes digitais que compõem o artefato.
     *
     * @return mixed
     */
    public function solicitarMetadados(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $solicitarMetadadosResponse = $this->executaBarramento('solicitarMetadados', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $solicitarMetadadosResponse;
    }

    /**
     * Recebe o conteúdo binário de cada componente digital tramitado. Após o recebimento do último, fica
     * permitido ao destinatário o envio do recebimento de trâmite, processo necessário para efetivação e
     * conclusão do fluxo.
     *
     * @return mixed
     */
    public function receberComponenteDigital(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $receberComponenteDigitalResponse = $this->executaBarramento('receberComponenteDigital', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $receberComponenteDigitalResponse;
    }

    /**
     * Registrar na solução o recebimento, pelo destinatário, do conteúdo tramitado. Para isso o SPE assina
     * uma cadeia de caracteres denominada como recibo e envia a assinatura para a solução. Esse recibo é
     * validado pela solução e disponibilizado para o SPE que solicitou o trâmite (remetente).
     *
     * @return mixed
     */
    public function enviarReciboDeTramite(stdClass $params): mixed
    {
        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $receberComponenteDigitalResponse = $this->executaBarramento('enviarReciboDeTramite', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $receberComponenteDigitalResponse;
    }

    /**
     * Recusar um trâmite, indicando motivo e justificativa. Esta recusa pode ocorrer a partir do envio dos
     * componentes digitais pelo SPE remetente, até o envio do recibo assinado pelo SPE de destino.
     *
     * @param int         $idt
     * @param int         $codMotivoRecusa
     * @param string|null $justificativa
     *
     * @return mixed
     */
    public function recusarTramite(int $idt, int $codMotivoRecusa = 99, ?string $justificativa = null): mixed
    {
        if (!isset($this->mapeamentos['motivo_recusa'][$codMotivoRecusa])) {
            $this->logger->critical("Código de motivo da recusa inexistente: $codMotivoRecusa.");

            return false;
        }

        if (!$justificativa) {
            $justificativa = $this->mapeamentos['motivo_recusa'][$codMotivoRecusa];
        }

        $params = new stdClass();
        $params->recusaDeTramite = new stdClass();
        $params->recusaDeTramite->IDT = $idt;
        $params->recusaDeTramite->justificativa = $justificativa;
        $params->recusaDeTramite->motivo = 99;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $receberComponenteDigitalResponse = $this->executaBarramento('recusarTramite', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);

            return false;
        }

        return $receberComponenteDigitalResponse;
    }

    /**
     * Envia ao barramento ciência de recusa quando houver.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function cienciaRecusa(int $idt): mixed
    {
        $params = new stdClass();
        $params->IDT = $idt;

        try {
            parent::iniciaRequisicao(__FUNCTION__);

            $receberComponenteDigitalResponse = $this->executaBarramento('cienciaRecusa', $params);
        } catch (SoapFault $e) {
            $this->processaErroBarramento($e);
            throw $e;
        }

        return $receberComponenteDigitalResponse;
    }

    /**
     * @param $function
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function executaBarramento($function, $params): mixed
    {
        $max_tentativas = 5;
        $tentativa = 0;

        do {
            try {
                $response = $this->client->$function($params);

                if ($response) {
                    return $response;
                } else {
                    $tentativa++;
                    sleep(5);
                }
            } catch (\Throwable $e) {
                if (!$this->client->__getLastResponse()) {
                    $tentativa++;
                    sleep(5);
                } else {
                    throw new \Exception($e->getMessage());
                }
            }
        } while ($tentativa <= $max_tentativas);

        throw new \Exception('Erro de conexão com o barramento.');
    }
}
