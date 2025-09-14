<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use function microtime;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\Entity\Processo;
use Throwable;

/**
 * Class AbstractBarramentoManager.
 */
abstract class AbstractBarramentoManager
{
    use BarramentoUtil;

    /**
     * Formatação de data utilizado no barramento.
     */
    public const FORMATO_DATA_BARRAMENTO = 'Y-m-d\TH:i:s.000P';

    /**
     * Serviço de manuseio de logs.
     */
    protected BarramentoLogger $logger;

    /**
     * Serviço de manuseio de logs.
     */
    protected BarramentoLogger $statistics;

    /**
     * Parâmetros de configuração do barramento.
     */
    protected array $config;

    /**
     * Mapeamentos do barramento.
     */
    protected array $mapeamentos;

    /**
     * Cliente Soap.
     */
    protected BarramentoClient $client;

    /**
     * Entity Manager.
     */
    protected EntityManager $em;

    /**
     * Response obtido por meio do request enviado ao Soap
     * Será utilizado para reuso dentro dos métodos das classes herdadas.
     */
    protected stdClass $response;

    /**
     * Status de trâmite no barramento: Aguardando envio de processos/documentos digitais.
     */
    public const SIT_AGUARDA_ENVIO_PROC_DOC = 1;

    /**
     * Status de trâmite no barramento: Processos/documentos digitais recebidos pelo barramento.
     */
    public const SIT_DOC_PROC_RECEBIDO_BARRAMENTO = 2;

    /**
     * Status de trâmite no barramento: Metadados recebidos pelo destinatário.
     */
    public const SIT_META_DADOS_RECEBIDO_DESTINATARIO = 3;

    /**
     * Status de trâmite no barramento: Processos/documentos digitais recebidos pelo destinatário.
     */
    public const SIT_DOC_PROC_RECEBIDO_DESTINATARIO = 4;

    /**
     * Status de trâmite no barramento: Recibo de conclusão do trâmite recebido pelo barramento.
     */
    public const SIT_RECIBO_CONCLUSAO_RECEBIDO_BARRAMENTO = 5;

    /**
     * Status de trâmite no barramento: Recibo de conclusão do trâmite recebido pelo remetente.
     */
    public const SIT_RECIBO_CONCLUSAO_RECEBIDO_REMETENTE = 6;

    /**
     * Status de trâmite no barramento: Cancelado.
     */
    public const SIT_CANCELADO = 7;

    /**
     * Status de trâmite no barramento: Aguardando Ciência.
     */
    public const SIT_AGUARDANDO_CIENCIA = 8;

    /**
     * Status de trâmite no barramento: Recusado pelo destinatário.
     */
    public const SIT_RECUSADO_DESTINATARIO = 9;

    /**
     * Indica que o processo/documento ainda está em sincronização com o barramento.
     */
    public const EM_SINCRONIZACAO = 0;

    /**
     * Indica que o processo/documento foi sincronizado com sucesso.
     */
    public const SINCRONIZACAO_SUCESSO = 1;

    /**
     * Indica ocorreu um erro na comunicação entre o sapiens e o barramento.
     */
    public const WSDL_FAIL = 2;

    /**
     * Indica ocorreu um erro no processamento do Gearman.
     */
    public const GEARMAN_FAIL = 3;

    /**
     * Indica ocorreu um erro na inclusão do statos da sincronização.
     */
    public const SINCRONIZACAO_STATUS_FAIL = 11;

    /**
     * Utilizada para indicar que a pasta não está mais em tramitação. Inserir no campo emTramitacao do objeto
     * Pasta quando necessário.
     */
    public const PASTA_NAO_ESTA_EM_TRAMITACAO = 0;

    private ProcessoResource $processoResource;

    private OrigemDadosResource $origemDadosResource;

    public function __construct(
        BarramentoLogger $logger,
        array $config,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource
    ) {
        $this->logger = $logger;
        $this->config = $config['config'];
        $this->mapeamentos = $config['mapeamentos'];
        $this->client = $barramentoClient;
        $this->processoResource = $processoResource;
        $this->origemDadosResource = $origemDadosResource;
    }

    /**
     * Retorna o código do erro ocorrido no Soap após a comunicação.
     */
    public function getCodigoErro(): ?string
    {
        return $this->client->getCodigoErro();
    }

    /**
     * Retorna a descrição do erro ocorrido no Soap após a comunicação.
     */
    public function getMensagemErro(): ?string
    {
        return $this->client->getMensagemErro();
    }

    /**
     * Retorna descrição da situação do trâmite.
     *
     * @param $codSituacaoTramite
     *
     * @throws Exception Código de situação de trâmite inexistente
     */
    protected function getDescricaoSituacaoTramite($codSituacaoTramite): string
    {
        $situacoesTramite = [
            self::SIT_AGUARDA_ENVIO_PROC_DOC => 'Aguardando envio de processos/documentos digitais',
            self::SIT_DOC_PROC_RECEBIDO_BARRAMENTO => 'Processos/documentos digitais recebidos pelo barramento',
            self::SIT_META_DADOS_RECEBIDO_DESTINATARIO => 'Metadados recebidos pelo destinatário',
            self::SIT_DOC_PROC_RECEBIDO_DESTINATARIO => 'Processos/documentos digitais recebidos pelo destinatário',
            self::SIT_RECIBO_CONCLUSAO_RECEBIDO_BARRAMENTO => 'Recibo de conclusão do trâmite recebido'.
                ' pelo barramento',
            self::SIT_RECIBO_CONCLUSAO_RECEBIDO_REMETENTE => 'Recibo de conclusão do trâmite recebido pelo '.
                'remetente',
            self::SIT_CANCELADO => 'Cancelado',
            self::SIT_AGUARDANDO_CIENCIA => 'Aguardando Ciência',
            self::SIT_RECUSADO_DESTINATARIO => 'Recusado pelo destinatário',
        ];

        if (!isset($situacoesTramite[$codSituacaoTramite])) {
            throw new Exception("Não existe situação de trâmite com o código: $codSituacaoTramite");
        }

        return $situacoesTramite[$codSituacaoTramite];
    }

    /**
     * @throws Throwable
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function setStatusFalha(string $protocolo, int $status, string $transactionId): bool
    {
        $tempoExecucao = microtime(true);

        try {
            $protocoloSemFormatacao = preg_replace('/\D/', '', $protocolo);

            /** @var Processo $processo */
            $processo = $this->processoResource->findOneBy(['NUP' => $protocoloSemFormatacao]);

            if ($processo && $processo->getOrigemDados()) {
                $dtoOrigemDados = (new OrigemDados())->setStatus($status);

                $this->origemDadosResource
                    ->update($processo->getOrigemDados()->getId(), $dtoOrigemDados, $transactionId);
            }
        } catch (Throwable $e) {
            $this->logger->critical("Houve um erro na alteração do status [$status]. ".
                "Erro: {$e->getMessage()}");

            throw $e;
        }

        $this->logger->info((string)(microtime(true) - $tempoExecucao), ['barramento_pen_stats']);

        return true;
    }

    /**
     * Obtém dados do Response.
     */
    protected function getResponse(): stdClass
    {
        return $this->response;
    }

    /**
     * Insere objeto do Response.
     */
    protected function setResponse(stdClass $response)
    {
        $this->response = $response;
    }
}
