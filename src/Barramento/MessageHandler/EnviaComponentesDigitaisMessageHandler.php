<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/EnviaComponentesDigitaisMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

use Exception;
use Redis;
use SuppCore\AdministrativoBackend\Barramento\Message\EnviaComponentesDigitaisMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoEnviaComponenteDigital;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoSolicitacao;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class EnviaComponentesDigitaisMessageHandler.
 */
#[AsMessageHandler]
class EnviaComponentesDigitaisMessageHandler
{
    private BarramentoLogger $logger;

    private BarramentoEnviaComponenteDigital $barramentoEnvioComponente;

    private BarramentoSolicitacao $barramentoSolicitacao;

    private TransactionManager $transactionManager;

    /**
     * EnviaComponentesDigitaisMessageHandler constructor.
     * @param BarramentoLogger $logger
     * @param BarramentoSolicitacao $barramentoSolicitacao
     * @param BarramentoEnviaComponenteDigital $enviaComponenteDigital
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        BarramentoLogger $logger,
        BarramentoSolicitacao $barramentoSolicitacao,
        BarramentoEnviaComponenteDigital $enviaComponenteDigital,
        TransactionManager $transactionManager,
        private Redis $redisClient
    ) {
        $this->logger = $logger;
        $this->barramentoSolicitacao = $barramentoSolicitacao;
        $this->barramentoEnvioComponente = $enviaComponenteDigital;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(EnviaComponentesDigitaisMessage $enviaComponentesDigitaisMessage)
    {
        $idt = $enviaComponentesDigitaisMessage->getIdt();

        // salva no redis o idt para controlar a execucao de processos repetidos
        if (!$this->redisClient->exists('envia_cd_idt_'.$idt)) {
            $this->redisClient->set('envia_cd_idt_'.$idt, 'processando');
            $this->redisClient->expire('envia_cd_idt_'.$idt, 2500);
            $transactionId = $this->transactionManager->begin();

            try {
                $this->barramentoEnvioComponente->enviaComponentesDigitais($idt, $transactionId);
                $this->transactionManager->commit($transactionId);
                $this->redisClient->set('envia_cd_idt_'.$idt, 'finalizado');
            } catch (Throwable $e) {
                $this->redisClient->del('envia_cd_idt_'.$idt);
                $this->logger->critical("Falha no EnviaComponentesDigitaisMessageHandler: {$e->getMessage()}");
                $this->barramentoSolicitacao->cancelaTramite($idt);
                $transactionId = $this->transactionManager->getCurrentTransactionId();
                if ($transactionId) {
                    $this->transactionManager->resetTransaction($transactionId);
                }
                $this->transactionManager->clearManager();
                throw $e;
            }
        }
    }
}
