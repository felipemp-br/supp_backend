<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Listeners;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Throwable;

/**
 * RequestListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RequestListener implements EventSubscriberInterface
{
    /**
     * Constructor.
     *
     * @param CircuitBreakerService $circuitBreakerService
     * @param LoggerInterface       $logger
     */
    public function __construct(
        private readonly CircuitBreakerService $circuitBreakerService,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onHttpRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     *
     * @return void
     *
     * @throws CircuitBreakerOpenException
     */
    public function onHttpRequest(
        RequestEvent $event
    ): void {
        try {
            if ($event->getRequest()->attributes->has('_controller')) {
                [$controller,] = explode('::', $event->getRequest()->attributes->get('_controller'));
                $resource = $this->circuitBreakerService->getResource($controller);
                if (!$resource) {
                    return;
                }
                $lastServiceKey = null;
                $lastRemaningOpenTime = 0;
                foreach ($resource->getServicesKeys() as $serviceKey) {
                    $isOpen = $this->circuitBreakerService->isCircuitOpen($serviceKey);
                    $remainingOpenTime = $this->circuitBreakerService->getCircuitBreakerRemainingOpenTime($serviceKey);
                    if ($isOpen
                        && $remainingOpenTime > $lastRemaningOpenTime) {
                        $lastRemaningOpenTime = $remainingOpenTime;
                        $lastServiceKey = $serviceKey;
                    }
                }
                if ($lastServiceKey) {
                    throw new CircuitBreakerOpenException(
                        $lastServiceKey,
                        $lastRemaningOpenTime
                    );
                }
            }
        } catch (CircuitBreakerOpenException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->logger->critical(
                sprintf(
                    '[Circuit Breaker] %s',
                    $e->getMessage()
                ),
                [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }
}
