<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Exception;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaDocumentoAvulsoMessage;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaProcessoMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Classe responsável por realizar o processamento das pendências do Sapiens junto ao barramento.
 */
class BarramentoSolicitacao extends AbstractBarramentoManager
{
    private DocumentoAvulsoResource $documentoAvulsoResource;

    private TransactionManager $transactionManager;

    protected array $config;

    /**
     * BarramentoSolicitacao constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        DocumentoAvulsoResource $documentoAvulsoResource,
        TransactionManager $transactionManager
    ) {
        $this->config = $parameterBag->get('integracao_barramento');
        $this->logger = $logger;
        $this->documentoAvulsoResource = $documentoAvulsoResource;
        $this->transactionManager = $transactionManager;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
    }

    /**
     * Inicia um trâmite de documento avulso. Os metadados do documento e seus componentes digitais são
     * informados nesse passo.
     *
     * @param int $identificacaoDoRepositorioDeEstruturasRemetente
     * @param int $numeroDeIdentificacaoDaEstruturaRemetente
     * @param int $identificacaoDoRepositorioDeEstruturasDestinatario
     * @param int $numeroDeIdentificacaoDaEstruturaDestinatario
     * @param int $documentoAvulsoId
     * @param $transactionId
     *
     * @throws Exception - Falha na conexão com o barramento 1
     */
    public function enviarDocumento(
        int $identificacaoDoRepositorioDeEstruturasRemetente,
        int $numeroDeIdentificacaoDaEstruturaRemetente,
        int $identificacaoDoRepositorioDeEstruturasDestinatario,
        int $numeroDeIdentificacaoDaEstruturaDestinatario,
        int $documentoAvulsoId,
        $transactionId
    ): void {
        $documentoAvulsoEntity = $this->documentoAvulsoResource->findOne($documentoAvulsoId);

        if (!$documentoAvulsoEntity) {
            throw new Exception('Documento Avulso inexistente.');
        }

        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 1. '.$this->getMensagemErro());
        }

        $dados = [
            'idRepositorioDeEstruturasRemetente' => $identificacaoDoRepositorioDeEstruturasRemetente,
            'idEstruturaRemetente' => $numeroDeIdentificacaoDaEstruturaRemetente,
            'idRepositorioEstruturasDestinatario' => $identificacaoDoRepositorioDeEstruturasDestinatario,
            'idEstruturaDestinatario' => $numeroDeIdentificacaoDaEstruturaDestinatario,
            'documentoAvulsoId' => $documentoAvulsoId,
        ];

        $this->logger->info('Solicitação de envio de documento avulso encaminhada com sucesso', $dados);

        $message = new EnviaDocumentoAvulsoMessage();

        $message->setIdRepositorioDeEstruturasRemetente($identificacaoDoRepositorioDeEstruturasRemetente);
        $message->setIdEstruturaRemetente($numeroDeIdentificacaoDaEstruturaRemetente);
        $message->setIdRepositorioEstruturasDestinatario($identificacaoDoRepositorioDeEstruturasDestinatario);
        $message->setIdEstruturaDestinatario($numeroDeIdentificacaoDaEstruturaDestinatario);
        $message->setDocumentoAvulsoId($documentoAvulsoId);

        $this->transactionManager->addAsyncDispatch($message, $transactionId);
    }

    /**
     * Inicia um trâmite de processo ao barramento com seus respectivos documentos e componentes digitais.
     *
     * @param $identificacaoDoRepositorioDeEstruturasRemetente
     * @param $numeroDeIdentificacaoDaEstruturaRemetente
     * @param $identificacaoDoRepositorioDeEstruturasDestinatario
     * @param $numeroDeIdentificacaoDaEstruturaDestinatario
     * @param $tramitacaoUuId
     * @param $transactionId
     */
    public function enviarProcesso(
        $identificacaoDoRepositorioDeEstruturasRemetente,
        $numeroDeIdentificacaoDaEstruturaRemetente,
        $identificacaoDoRepositorioDeEstruturasDestinatario,
        $numeroDeIdentificacaoDaEstruturaDestinatario,
        $tramitacaoUuId,
        $transactionId
    ) {
        $dados = [
            'idRepositorioDeEstruturasRemetente' => $identificacaoDoRepositorioDeEstruturasRemetente,
            'idEstruturaRemetente' => $numeroDeIdentificacaoDaEstruturaRemetente,
            'idRepositorioEstruturasDestinatario' => $identificacaoDoRepositorioDeEstruturasDestinatario,
            'idEstruturaDestinatario' => $numeroDeIdentificacaoDaEstruturaDestinatario,
            'tramitacaoId' => $tramitacaoUuId,
        ];
        $this->logger->info('Solicitação de envio de processo encaminhada com sucesso', $dados);

        $enviaProcessoMessage = new EnviaProcessoMessage();
        $enviaProcessoMessage->setIdRepositorioDeEstruturasRemetente($identificacaoDoRepositorioDeEstruturasRemetente);
        $enviaProcessoMessage->setIdEstruturaRemetente($numeroDeIdentificacaoDaEstruturaRemetente);
        $enviaProcessoMessage
            ->setIdRepositorioEstruturasDestinatario($identificacaoDoRepositorioDeEstruturasDestinatario);
        $enviaProcessoMessage->setIdEstruturaDestinatario($numeroDeIdentificacaoDaEstruturaDestinatario);
        $enviaProcessoMessage->setTramitacaoUuid($tramitacaoUuId);
        // Dispara a mensagem para o processador

        $this->transactionManager->addAsyncDispatch($enviaProcessoMessage, $transactionId);
    }

    /**
     * Cancelar um trâmite que ainda não foi concluído. Este operação pode ser executada enquanto o SPE de
     * destino não enviar o recibo assinado para a solução.
     *
     * @param int $ticketBarramento - Este dado pode ser obtido no campo ticketBarramento
     *                              do objeto Comunicacao ou Tramitacao
     *
     * @throws exception - Falha na conexão com o barramento
     */
    public function cancelaTramite(int $ticketBarramento): bool
    {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 3. '.$this->getMensagemErro());
        }

        $this->client->cancelarEnvioDeTramite($ticketBarramento);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return true;
    }

    /**
     * Fornece a lista de repositórios de estruturas organizacionais, insumo necessário para que o SPE possa
     * fornecer uma interface para seu usuário procurar o destinatário do trâmite desejado.
     *
     * @return stdClass|false
     *
     * @throws exception - Falha na conexão com o barramento
     */
    public function consultarRepositorios(): bool|stdClass
    {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 4. '.$this->getMensagemErro());
        }

        $response = $this->client->consultarRepositoriosDeEstruturas();

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return $response;
    }

    /**
     * Fornece, em estrutura de lista, permitindo filtros, informações das estruturas organizacionais de um
     * determinado repositório de estruturas.
     *
     * @param int $idRepositorio - Id do repositório obtido na lista
     *                           retornada pelo método consultarRepositorios()
     * @param string|null $idEstrutura     - Id da estrutura quando houver. Não Obrigatório.
     * @param string|null $nomeDaEstrutura - Nome da estrutura a ser consultada. Não Obrigatório.
     *
     * @return stdClass|false
     *
     * @throws Exception - Falha na conexão com o barramento
     */
    public function consultarEstruturas(
        int $idRepositorio,
        ?string $idEstrutura = null,
        ?string $nomeDaEstrutura = null
    ): bool|stdClass {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 5. '.$this->getMensagemErro());
        }

        $response = $this->client->consultarEstruturas($idRepositorio, $idEstrutura, $nomeDaEstrutura);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return $response;
    }

    /**
     * Recusar um trâmite, indicando motivo e justificativa. Esta recusa pode ocorrer a partir do envio dos
     * componentes digitais pelo SPE remetente, até o envio do recibo assinado pelo SPE de destino.
     *
     * @param int         $ticketBarramento - Este dado pode ser obtido no campo ticketBarramento
     *                                      do objeto Comunicacao ou Tramitacao
     * @param int         $codMotivoRecusa  - Não obrigatório e poderá ser encontrado nos mapeamentos nos arquivos de
     *                                      configuração do MainBundle
     * @param string|null $justificativa    - Descrição livre do motivo da recusa
     *
     * @return bool
     *
     * @throws Exception - Falha na conexão com o barramento
     */
    public function recusarTramite(
        int $ticketBarramento,
        int $codMotivoRecusa = 99,
        ?string $justificativa = null
    ): bool {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 6. '.$this->getMensagemErro());
        }

        $this->client->recusarTramite($ticketBarramento, $codMotivoRecusa, $justificativa);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return true;
    }

    /**
     * Fornece informações dos trâmites relacionados ao SPE, como números de identificação, histórico de
     * situações, entre várias informações.
     *
     * @param int|null    $ticketBarramento - Este dado pode ser obtido no campo ticketBarramento
     *                                      do objeto Comunicacao ou Tramitacao
     * @param string|null $protocolo        - Procolo / NUP
     * @param int|null    $situacao         - Veja situações nas constantes da classe pai "SIT_"
     *
     * @return stdClass|false
     *
     * @throws Exception - Falha na conexão com o barramento
     */
    public function consultarTramites(
        ?int $ticketBarramento = null,
        ?string $protocolo = null,
        ?int $situacao = null
    ): bool|stdClass {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 7. '.$this->getMensagemErro());
        }

        $response = $this->client->consultarTramites($ticketBarramento, $protocolo, $situacao);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return $response;
    }

    /**
     * Envia ao barramento ciência de recusa quando houver.
     *
     * @param int $ticketBarramento - Este dado pode ser obtido no campo ticketBarramento
     *                              do objeto Comunicacao ou Tramitacao
     *
     * @return bool
     *
     * @throws exception - Falha na conexão com o barramento
     * @throws Throwable
     */
    public function enviarCienciaRecusa(int $ticketBarramento): bool
    {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 8. '.$this->getMensagemErro());
        }

        $this->client->cienciaRecusa($ticketBarramento);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return true;
    }

    /**
     * Reproduzir o último trâmite para um determinado destinatário. Esse serviço tem o intuito de suprir o caso
     * onde o SPE de destino deseja receber novamente um conteúdo que ele já recebeu anteriormente.
     *
     * @param int $ticketBarramento                       - Este dado pode ser obtido no campo ticketBarramento
     *                                                    do objeto Comunicacao ou Tramitacao
     * @param int $identificacaoDoRepositorioDeEstruturas - Obitido no consultarRepositoriosDeEstruturas
     * @param int $numeroDeIdentificacaoDaEstrutura       - Obitido no consultarEstruturas
     *
     * @throws exception - Falha na conexão com o barramento
     */
    public function reproduzirUltimoTramite(
        int $idt,
        int $identificacaoDoRepositorioDeEstruturas,
        int $numeroDeIdentificacaoDaEstrutura
    ): bool|stdClass {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 9. '.$this->getMensagemErro());
        }

        $response = $this->client->consultarTramites($idt);

        if (is_array($response->tramitesEncontrados)) {
            $tramite = $response->tramitesEncontrados[0];
        } else {
            $tramite = $response->tramitesEncontrados;
        }

        if (!isset($tramite->tramite->NRE)) {
            return false;
        }

        $nre = $tramite->tramite->NRE;

        $response = $this->client->reproduzirUltimoTramite(
            (string) $nre,
            $identificacaoDoRepositorioDeEstruturas,
            $numeroDeIdentificacaoDaEstrutura
        );

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return $response;
    }

    /**
     * Envia ao barramento ciência de recusa quando houver.
     *
     * @param int $ticketBarramento - Este dado pode ser obtido no campo ticketBarramento
     *                              do objeto Comunicacao ou Tramitacao
     *
     * @return stdClass|false
     *
     * @throws exception - Falha na conexão com o barramento
     * @throws Throwable
     */
    public function receberReciboDeTramite(int $ticketBarramento): bool|stdClass
    {
        // Houve problema de conexão
        if ($this->getMensagemErro()) {
            throw new Exception('Falha na conexão com o barramento 10. '.$this->getMensagemErro());
        }

        $response = $this->client->receberReciboDeTramite($ticketBarramento);

        if ($this->client->getCodigoErro()) {
            return false;
        }

        return $response;
    }
}
