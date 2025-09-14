<?php

declare(strict_types=1);
/**
 * /src/Cronjob/CronjobLogger.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
namespace SuppCore\AdministrativoBackend\Cronjob;

use DateTimeInterface;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class CronjobLogger.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobLogger implements CronjobLoggerInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param string               $message
     * @param EntityInterface|null $cronJob
     * @param array                $context
     *
     * @return void
     */
    public function info(string $message, ?EntityInterface $cronJob = null, array $context = []): void {
        if ($cronJob) {
            $context['id'] = $cronJob->getId();
            $context['uuid'] = $cronJob->getUuid();
            $context['comando'] = $cronJob->getComando();
            $context['periodicidade'] = $cronJob->getPeriodicidade();
        }

        $this->logger->info($message, $context);
    }

    /**
     * @param string               $message
     * @param string               $error
     * @param EntityInterface|null $cronJob
     * @param array                $context
     * @return void
     */
    public function error(string $message, string $error, ?EntityInterface $cronJob = null, array $context = []): void {
        $context['output'] = $error;
        if ($cronJob) {
            $context['id'] = $cronJob->getId();
            $context['uuid'] = $cronJob->getUuid();
            $context['comando'] = $cronJob->getComando();
            $context['periodicidade'] = $cronJob->getPeriodicidade();
            $context['pid'] = $cronJob->getUltimoPid();
        }

        $this->logger->error($message, $context);
    }

}
