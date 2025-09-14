<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Psr\Log\LoggerInterface;

/**
 * Classe responsável por criação de logs para as integrações.
 *
 * Esta solução teve que ser adotada, pois os logs injetados diretamente nas services não estavam funcionando quando
 * executados no ambiente de produção.
 *
 */
abstract class AbstractLogger
{
    private LoggerInterface $logger;

    /**
     * Cria um log no canal de informações e estatísticas.
     *
     * @param $mensagem
     * @param array $dados
     */
    public function info($mensagem, array $dados = [])
    {
        $this->getLogger()->info($mensagem, $dados);
    }

    /**
     * Cria um log no canal comum, voltado para erros ocorridos na aplicação.
     *
     * @param $mensagem
     * @param array $dados
     */
    public function critical($mensagem, array $dados = [])
    {
        $this->getLogger()->critical($mensagem, $dados);
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return LoggerInterface
     */
    protected function setLogger(LoggerInterface $logger): LoggerInterface
    {
        return $this->logger = $logger;
    }
}
