<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Listeners;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;

/**
 * CommandListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CommandListener implements EventSubscriberInterface
{
    /**
     * Constructor.
     *
     * @param CircuitBreakerService $circuitBreakerService
     * @param LoggerInterface       $logger
     */
    public function __construct(
        private readonly CircuitBreakerService $circuitBreakerService,
        private readonly LoggerInterface $logger,
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
        try {
            $commandName = get_class($event->getCommand());
            $resource = $this->circuitBreakerService->getResource($commandName);
            if (!$resource) {
                return;
            }
            foreach ($resource->getServicesKeys() as $serviceKey) {
                $isOpen = $this->circuitBreakerService->isCircuitOpen($serviceKey);
                if ($isOpen) {
                    $e = new CircuitBreakerOpenException(
                        $serviceKey,
                        $this->circuitBreakerService->getCircuitBreakerRemainingOpenTime($serviceKey)
                    );
                    $event
                        ->getOutput()
                        ->writeln(
                            $e->getMessage()
                        );
                    $event->disableCommand();
                    // Sleep que funciona em conjunto com o startsecs do supervisord.
                    // Dessa forma o supervisord fica tentando restartar sem dar erro FATAL.
                    sleep(2);
                    throw $e;
                }
            }

        } catch (CircuitBreakerOpenException $e){
            throw $e;
        } catch (Throwable $e){
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
