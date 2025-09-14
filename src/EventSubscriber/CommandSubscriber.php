<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EventSubscriber;

use SuppCore\AdministrativoBackend\Utils\TraceService;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * CommandSubscriber.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CommandSubscriber implements EventSubscriberInterface
{
    /**
     * Constructor.
     *
     * @param TraceService $traceService
     */
    public function __construct(
        private readonly TraceService $traceService,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     *
     * @return void
     */
    public function onCommand(
        ConsoleCommandEvent $event
    ): void {
        $this->traceService->loadTraceIdFromEnvironment();
    }

}
