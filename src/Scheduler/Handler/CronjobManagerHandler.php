<?php

declare(strict_types=1);
/**
 * /src/Scheduler/Message/CronjobManagerHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Scheduler\Handler;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CronjobResource;
use SuppCore\AdministrativoBackend\Scheduler\Message\CronjobManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\TraceService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class CronjobManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class CronjobManagerHandler
{
    public function __construct(
        private readonly CronjobResource $cronJobResource,
        private readonly LoggerInterface $logger,
        private readonly TransactionManager $transactionManager,
        private readonly TraceService $traceService,
    ) {
    }

    public function __invoke(CronjobManager $message): void
    {
        try {
            $this->traceService->restartTraceId();
            $this->logger->info(
                'Reiniciando identificador de trace para manter a individualidade da execução do scheduler.'
            );
            $transactionId = $this->transactionManager->begin();
            $cronJobDTO = $this->cronJobResource->getDtoForEntity(
                $message->getId(),
                $this->cronJobResource->getDtoClass()
            );
            $this->logger->info(sprintf('cronjob_manager_handler: Iniciando o cronjob %s', $message->getId()));
            $this->cronJobResource->startJob($message->getId(), $cronJobDTO, $transactionId);
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $exception) {
            $this->logger->error('cronjob_manager_handler: '.$exception->getMessage());
        }
    }
}
