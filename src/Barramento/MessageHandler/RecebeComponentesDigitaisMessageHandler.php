<?php

declare(strict_types=1);

/**
 * /src/MessageHandler/RecebeComponentesDigitaisMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Barramento\MessageHandler;

use Exception;
use Redis;
use SuppCore\AdministrativoBackend\Barramento\Message\RecebeComponentesDigitaisMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoLogger;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoRecebeComponenteDigital;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoSolicitacao;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class RecebeComponentesDigitaisMessageHandler.
 */
#[AsMessageHandler]
class RecebeComponentesDigitaisMessageHandler
{
    private BarramentoLogger $logger;

    private BarramentoSolicitacao $barramentoSolicitacao;

    private BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital;

    private TransactionManager $transactionManager;

    /**
     * RecebeComponentesDigitaisMessageHandler constructor.
     * @param BarramentoLogger $logger
     * @param BarramentoSolicitacao $barramentoSolicitacao
     * @param BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        BarramentoLogger $logger,
        BarramentoSolicitacao $barramentoSolicitacao,
        BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital,
        TransactionManager $transactionManager,
        private Redis $redisClient
    ) {
        $this->logger = $logger;
        $this->barramentoSolicitacao = $barramentoSolicitacao;
        $this->barramentoRecebeComponenteDigital = $barramentoRecebeComponenteDigital;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __invoke(RecebeComponentesDigitaisMessage $recebeComponentesDigitaisMessageHandler)
    {
        $idt = $recebeComponentesDigitaisMessageHandler->getIdt();

        // salva no redis o idt para controlar a execucao de processos repetidos
        if (!$this->redisClient->exists('recebe_cd_idt_'.$idt)) {
            $this->redisClient->set('recebe_cd_idt_'.$idt, 'processando');
            $this->redisClient->expire('recebe_cd_idt_'.$idt, 2500);

            $transactionId = $this->transactionManager->begin();

            try {
                $this->barramentoRecebeComponenteDigital->receberComponentesDigitais($idt, $transactionId);
                $this->transactionManager->commit($transactionId);
                $this->redisClient->set('recebe_cd_idt_'.$idt, 'finalizado');
            } catch (Throwable $e) {
                $mensagem = strlen($e->getMessage()) < 255 ? $e->getMessage() : 'Erro ao receber os componentes digitais.';
                // Rollback Transaction
                $this->barramentoSolicitacao->recusarTramite(
                    $idt,
                    99,
                    $mensagem
                );
                $this->redisClient->del('recebe_cd_idt_'.$idt);
                $this->logger->critical(
                    "Falha no RecebeComponentesDigitaisMessageHandler: 
            {$e->getMessage()}.' - '.{$e->getTraceAsString()}"
                );
                $transactionId = $this->transactionManager->getCurrentTransactionId();
                if ($transactionId) {
                    $this->transactionManager->resetTransaction($transactionId);
                }
                $this->transactionManager->clearManager();

                //cria uma nova transacao apenas para o catch
                $transactionIdCatch = $this->transactionManager->begin();
                $this->barramentoRecebeComponenteDigital->alteraStatusSincronizacao(2, $transactionIdCatch);
                $this->transactionManager->commit($transactionIdCatch);
                throw $e;
            }
        }
    }
}
