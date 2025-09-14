<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Listeners;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionParameter;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces\MessengerFileLocatorInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\MessengerFile;
use SuppCore\AdministrativoBackend\CircuitBreaker\Services\CircuitBreakerService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\VarExporter\LazyObjectInterface;
use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * MessengerListener.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MessengerListener implements EventSubscriberInterface
{
    private const string CIRCUIT_BREAKER_MESSAGE_CONSUMER_LISTENER = 'CIRCUIT_BREAKER_MESSAGE_CONSUMER_LISTENER';
    private const int TTL_EXPIRATION = 3600;
    private ?Envelope $lastEnvelop = null;

    /**
     * Constructor.
     *
     * @param CircuitBreakerService  $circuitBreakerService
     * @param CacheItemPoolInterface $cacheItemPool
     * @param LoggerInterface        $logger
     * @param MessageBusInterface    $bus
     * @param iterable               $fileLocators
     * @param iterable               $messengerHandlers
     */
    public function __construct(
        private readonly CircuitBreakerService $circuitBreakerService,
        private readonly CacheItemPoolInterface $cacheItemPool,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $bus,
        #[TaggedIterator(MessengerFileLocatorInterface::MESSENGER_FILE_LOCATOR_TAG)]
        private readonly iterable $fileLocators,
        #[TaggedIterator('messenger.message_handler')]
        private readonly iterable $messengerHandlers,
    ) {
    }

    /**
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function getMessengerConfigs(): array
    {
        $data = [];
        $cacheItem = $this->cacheItemPool->getItem(self::CIRCUIT_BREAKER_MESSAGE_CONSUMER_LISTENER);
        if (!$cacheItem->isHit()) {
            try {
                /** @var MessengerFile $messengerFile */
                foreach (iterator_to_array($this->fileLocators) as $messengerFileLocatorService) {
                    $messengerFile = $messengerFileLocatorService->getMessengerFile();
                    $locator = new FileLocator([$messengerFile->getPath()]);
                    $configPath = $locator->locate($messengerFile->getFilename());
                    $config = Yaml::parseFile($configPath);
                    if (isset($config['framework']['messenger']['routing'])) {
                        $routing = $config['framework']['messenger']['routing'];
                        foreach ($this->messengerHandlers as $messengerHandler) {
                            $reflectionClass = new ReflectionClass($messengerHandler);
                            if ($reflectionClass->implementsInterface(LazyObjectInterface::class)) {
                                $reflectionClass = $reflectionClass->getParentClass();
                            }
                            $reflecionMethod = $reflectionClass->getMethod('__invoke');
                            /** @var ReflectionParameter $reflectionParameter */
                            $reflectionParameter = $reflecionMethod->getParameters()[0];
                            $message = $reflectionParameter->getType()->getName();
                            if (isset($routing[$message])) {
                                $data[$routing[$message]][] = $reflectionClass->getName();
                            }
                        }
                    }
                }
            } catch (Throwable $e) {
                $this->logger->critical(
                    sprintf(
                        '[Circuit Breaker] %s',
                        $e->getMessage()
                    ),
                    [
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTraceAsString(),
                    ]
                );
            }
            $cacheItem->set($data);
            $cacheItem->expiresAfter(self::TTL_EXPIRATION);
            $this->cacheItemPool->save($cacheItem);
        }

        return $cacheItem->get();
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
     *
     * @throws CircuitBreakerOpenException
     */
    public function onCommand(
        ConsoleCommandEvent $event
    ): void {
        try {
            $command = $event->getCommand();
            if ($command instanceof ConsumeMessagesCommand) {
                $receivers = $event->getInput()->getArgument('receivers');
                $data = $this->getMessengerConfigs();
                if (!$receivers) {
                    $receivers = array_keys($data);
                }
                foreach ($receivers as $receiver) {
                    if (isset($data[$receiver])) {
                        foreach ($data[$receiver] as $messageHandler) {
                            $resource = $this->circuitBreakerService->getResource($messageHandler);
                            if (!$resource) {
                                continue;
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
                        }
                    }
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
                    'trace' => $e->getTraceAsString(),
                ]
            );
        }
    }

    /**
     * @param WorkerRunningEvent $event
     *
     * @return void
     *
     * @throws CircuitBreakerOpenException
     */
    #[AsEventListener(event: WorkerRunningEvent::class)]
    public function onWorkerRunningEvent(
        WorkerRunningEvent $event
    ): void {
        try {
            foreach ($event->getWorker()->getMetadata()->getTransportNames() as $receiver) {
                $data = $this->getMessengerConfigs();
                if (isset($data[$receiver])) {
                    foreach ($data[$receiver] as $messageHandler) {
                        $resource = $this->circuitBreakerService->getResource($messageHandler);
                        if (!$resource) {
                            continue;
                        }
                        $lastServiceKey = null;
                        $lastRemaningOpenTime = 0;
                        foreach ($resource->getServicesKeys() as $serviceKey) {
                            $isOpen = $this->circuitBreakerService->isCircuitOpen($serviceKey);
                            $remainingOpenTime = $this->circuitBreakerService->getCircuitBreakerRemainingOpenTime(
                                $serviceKey
                            );
                            if ($isOpen
                                && $remainingOpenTime > $lastRemaningOpenTime) {
                                $lastRemaningOpenTime = $remainingOpenTime;
                                $lastServiceKey = $serviceKey;
                            }
                        }
                        if ($lastServiceKey) {
                            $e = new CircuitBreakerOpenException(
                                $lastServiceKey,
                                $lastRemaningOpenTime
                            );
                            $this->logger->error(
                                $e->getMessage()
                            );
                            $event->getWorker()->stop();
                            if (!$event->isWorkerIdle() && $this->lastEnvelop) {
                                $this->bus->dispatch(
                                    new Envelope(
                                        $this->lastEnvelop->getMessage(),
                                        [new DelayStamp($e->getRemainingOpenTime()*1000)]
                                    )
                                );
                            }
                            throw $e;
                        }
                    }
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
                    'trace' => $e->getTraceAsString(),
                ]
            );
        }
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
        $this->lastEnvelop = $event->getEnvelope();
    }

}
