<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use SoapFault;
use SuppCore\AdministrativoBackend\BeSimple\SoapClient\SoapClient;
use SuppCore\AdministrativoBackend\BeSimple\SoapCommon\Helper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Service o qual executa a conexção por meio do soap client.
 *
 * @codeCoverageIgnore
 */
class BarramentoSoap
{
    /**
     * Serviço de manuseio de logs.
     */
    protected BarramentoLogger $logger;

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
    protected ?SoapClient $client;

    /**
     * Código do erro.
     */
    private ?string $codigoErro = null;

    /**
     * Mensagem do erro.
     */
    private ?string $mensagemErro = null;

    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag
    ) {
        $this->logger = $logger;
        $this->config = $parameterBag->get('integracao_barramento')['config'];
        $this->mapeamentos = $parameterBag->get('integracao_barramento')['mapeamentos'];
        $this->client = null;
    }

    /**
     * Formata excessão para geração de log.
     *
     * @return void
     */
    protected function processaErroBarramento(SoapFault $exception)
    {
        if (isset($exception->detail)) {
            $this->codigoErro = (string) $exception->detail->interoperabilidadeException->codigoErro;
            $this->mensagemErro = $exception->detail->interoperabilidadeException->mensagem;
        } else {
            $this->codigoErro = (string) $exception->getCode();
            $this->mensagemErro = $exception->getMessage();
        }
        $this->logger->critical("Erro ($this->codigoErro): $this->mensagemErro - ".$exception->getTraceAsString());
    }

    /**
     * Realiza rotina inicial em comum para todos os serviços.
     *
     * @param string $serviceName Nome do serviço no barramento
     *
     * @throws SoapFault - Falha na conexão com o barramento
     */
    protected function iniciaRequisicao(string $serviceName)
    {
        $this->limpaMensagemErro();

        if (!$this->client) {
            $this->conectaBarramento();
        }

        $informacoes = ['servico' => $serviceName];

        if (!$this->client) {
            throw new SoapFault('Server', 'Falha na conexão com o barramento. Sem cliente.');
        }

        $this->logger->info('Iniciando requisição com o barramento.', $informacoes);
    }

    /**
     * Retorna código do erro ocorrido na comunicação.
     *
     * @return string|null
     */
    public function getCodigoErro()
    {
        return $this->codigoErro;
    }

    /**
     * Retorna mensagem do erro ocorrido na comunicação.
     *
     * @return string|null
     */
    public function getMensagemErro()
    {
        return $this->mensagemErro;
    }

    /**
     * Limpa código e mensagem de erro gerados na última requisição.
     */
    protected function limpaMensagemErro()
    {
        $this->codigoErro = null;
        $this->mensagemErro = '';
    }

    /**
     * Conecta ao barramento.
     */
    private function conectaBarramento()
    {
        $parametros = [
            'soap_version' => SOAP_1_1,
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 60,
            'timeout' => 360,
            'attachment_type' => Helper::ATTACHMENTS_TYPE_MTOM,
            'cache_wsdl' => WSDL_CACHE_DISK,
            'local_cert' => $this->config['certificado'],
            'passphrase' => $this->config['cert_senha'],
        ];
        $sucesso = false;
        $tentativa = 0;

        while (false === $sucesso) {
            try {
                $this->client = new SoapClient($this->config['url_soap'], $parametros);
                if ($this->client) {
                    $sucesso = true;
                } else {
                    if ($tentativa <= 5) {
                        sleep(5);
                        ++$tentativa;
                    } else {
                        break;
                    }
                }
            } catch (Throwable $e) {
                if ($tentativa <= 5) {
                    sleep(5);
                    ++$tentativa;
                } else {
                    throw new \Exception('Erro de conexão com o barramento.');
                    break;
                }
            }
        }

        if (!$this->client) {
            throw new \Exception('Erro de conexão com o barramento.');
        }
    }

    /**
     * Realiza 5 tentativas de conexão com o barramento para verificar se este está disponível.
     */
    private function testaConexao()
    {
        $sucesso = false;
        $tentativa = 0;
        while (false === $sucesso) {
            $handle = curl_init($this->config['url_soap']);
            curl_setopt($handle, CURLOPT_SSLCERT, $this->config['certificado']);
            curl_setopt($handle, CURLOPT_SSLCERTPASSWD, $this->config['cert_senha']);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);
            if (200 == $httpCode) {
                $sucesso = true;
            } else {
                if ($tentativa <= 5) {
                    sleep(1);
                    ++$tentativa;
                } else {
                    break;
                }
            }
        }

        return $sucesso;
    }
}
