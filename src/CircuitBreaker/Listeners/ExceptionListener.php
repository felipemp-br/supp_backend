<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Listeners;

use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * ExceptionListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionListener
{
    /**
     * Constructor.
     *
     * @param CircuitBreakerService $circuitBreakerService
     */
    public function __construct(
        private readonly CircuitBreakerService $circuitBreakerService,
    ) {
    }

    /**
     * @param ExceptionEvent $event
     *
     * @return void
     */
    public function __invoke(
        ExceptionEvent $event
    ): void {
        $exception = $this->circuitBreakerService->handleException($event->getThrowable());
        if ($exception) {
            $event->setThrowable($exception);
        }
    }
}
