<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EventListener;

use SuppCore\AdministrativoBackend\Utils\TraceService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;

/**
 * MessengerListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MessengerListener
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
     * @param SendMessageToTransportsEvent $event
     *
     * @return void
     */
    #[AsEventListener(event: SendMessageToTransportsEvent::class)]
    public function onSendMessageToTransportsEvent(
        SendMessageToTransportsEvent $event
    ): void {
        $event->setEnvelope(
            $this->traceService->addTraceStamp(
                $event->getEnvelope()
            )
        );
    }

    /**
     * @param WorkerMessageReceivedEvent $event
     *
     * @return void
     */
    #[AsEventListener(event: WorkerMessageReceivedEvent::class)]
    public function onWorkerMessageReceivedEvent(
        WorkerMessageReceivedEvent $event
    ): void {
        $this->traceService->loadTraceIdFromEnvelope(
            $event->getEnvelope()
        );
    }
}
