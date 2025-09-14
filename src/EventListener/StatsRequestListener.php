<?php

declare(strict_types=1);
/**
 * /src/EventListener/SoftdeleteableListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class StatsRequestListener.
 */
class StatsRequestListener
{
    private LoggerInterface $logger;

    /**
     * StatsRequestListener constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param TerminateEvent $event
     */
    public function onControllerResponse(TerminateEvent $event)
    {
        $this->logger->info('stats');
    }
}
