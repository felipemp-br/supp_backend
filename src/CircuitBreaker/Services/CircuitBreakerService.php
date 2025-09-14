<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Services;

use Closure;
use Psr\Log\LoggerInterface;
use Redis;
use Stringable;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\CircuitBreakerOpenException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\NotFoundServiceKeyException;
use SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces\RegisterCircuitBreakersInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces\RegisterResourceInterface;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreak;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakConfig;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakResource;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakStatus;
use SuppCore\AdministrativoBackend\CircuitBreaker\Model\FailureEvaluation;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Throwable;
use Traversable;

/**
 * CircuitBreakerService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CircuitBreakerService
{
    /**
     * @var array<string, CircuitBreak> circuitBreakers
     */
    protected array $circuitBreakers = [];

    /**
     * @var array<string, CircuitBreakResource> circuitBreakersResource
     */
    protected array $circuitBreakersResource = [];

    /**
     * Constructor.
     *
     * @param Redis           $redis
     * @param LoggerInterface $logger
     * @param Traversable     $registerResources
     * @param Traversable     $registerCircuitBreakers
     */
    public function __construct(
        private readonly Redis $redis,
        private readonly LoggerInterface $logger,
        #[TaggedIterator(RegisterResourceInterface::REGISTER_RESOURCE_TAG)]
        Traversable $registerResources,
        #[TaggedIterator(RegisterCircuitBreakersInterface::REGISTER_CIRCUIT_BREAKERS_TAG)]
        Traversable $registerCircuitBreakers,
    ) {
        /** @var RegisterResourceInterface $resource */
        foreach (iterator_to_array($registerResources) as $resource) {
            $this->registerResource($resource->getCircuitBreakerResource());
        }
        /** @var RegisterCircuitBreakersInterface $circuitBreakers */
        foreach (iterator_to_array($registerCircuitBreakers) as $circuitBreakers) {
            foreach ($circuitBreakers->getCircuitBreakers() as $circuitBreak)
            $this->registerCircuitBreak($circuitBreak);
        }
    }

    /**
     * Função para controle manual de circuito.
     * Exemplo de uso:
     * ```php
     *
     *      $circuitBreakerService->execute(
     *          'teste',
     *          fn () => $this->usuarioResource->create($usuarioDTO, $transactionId),
     *          function (CircuitBreakStatus $status) {
     *              $this->logger->info('O recurso está bloqueado.');
     *              return true;
     *          },
     *          fn (CircuitBreakStatus $status, Throwable $e) => $e instanceof RateLimitException
     *              ? new FailureEvaluation(true, $e->getRateLimitResetTime(), 1) : null,
     *          new CircuitBreakResource(MeuMessageHandler::class, ['teste'])
     *      );
     * ```
     *
     *
     * @param string       $serviceKey      Chave do serviço.
     * @param Closure      $onCircuitClosed Função executada quando o circuito estiver fechado.
     * @param Closure|null $onCircuitOpen   Função executada quando o circuito estiver aberto. Se retornar true será lançada exceção.
     * @param Closure|null $evaluateFailure Função executada para avaliar se o circuito deve tratar o erro.
     * @param string|null  $resource        Classname do resource que será observado nos listeners (commands, messages e controllers).
     *
     * @return mixed
     *
     * @throws CircuitBreakerOpenException
     * @throws Throwable
     */
    public function execute (
        string $serviceKey,
        Closure $onCircuitClosed,
        ?Closure $onCircuitOpen = null,
        ?Closure $evaluateFailure = null,
        ?string $resource = null,
    ): mixed {
        try {
            $this->registerCircuitBreak(
                new CircuitBreak(
                    $serviceKey,
                    null,
                )
            );
            if ($resource) {
                $this->registerResource(
                    new CircuitBreakResource(
                        $resource,
                        [
                            $serviceKey
                        ]
                    )
                );
            }
            $status = $this->getCircuitBreakStatus($serviceKey);
            if ($status->isOpen()) {
                if ($onCircuitOpen) {
                    if (!$onCircuitOpen($status)) {
                        return null;
                    }
                }
                throw new CircuitBreakerOpenException(
                    $serviceKey,
                    $status->getRemainingOpenTime()
                );
            }
            return $onCircuitClosed();
        } catch (CircuitBreakerOpenException $e) {
            throw $e;
        } catch (Throwable $e) {
            if ($evaluateFailure) {
                /** @var FailureEvaluation $failureEvaluation */
                $failureEvaluation = $evaluateFailure(
                    $status,
                    $e
                );
                if ($failureEvaluation) {
                    $circuitBreak = $this->getCircuitBreak($serviceKey);
                    $circuitBreak->setConfig(
                        new CircuitBreakConfig(
                            $failureEvaluation->getTimeout(),
                            $failureEvaluation->getThreshold(),
                            [],
                        )
                    );
                    if ($failureEvaluation->mustOpenCircuit()) {
                        $this->openCircuit($circuitBreak);
                        $status = $this->getCircuitBreakStatus($circuitBreak);
                    } else {
                        $status = $this->recordCircuitBreakFailure($circuitBreak);
                    }
                    if ($status->isOpen()) {
                        if ($onCircuitOpen) {
                            if (!$onCircuitOpen($status)) {
                                return null;
                            }
                        }
                        throw new CircuitBreakerOpenException(
                            $serviceKey,
                            $status->getRemainingOpenTime(),
                            $e
                        );
                    }
                }
            }
            throw $e;
        }
    }

    /**
     * @param Stringable|string $serviceKey
     *
     * @return string
     */
    private function getCacheFailureKey(
        Stringable|string $serviceKey
    ): string {
        return hash('sha256', "circuit:{$serviceKey}:failures");
    }

    /**
     * @param Stringable|string $serviceKey
     *
     * @return string
     */
    private function getCacheStatusKey(
        Stringable|string $serviceKey
    ): string {
        return hash('sha256', "circuit:{$serviceKey}:open");
    }

    /**
     * Verifica se o Circuit Breaker está aberto.
     *
     * @param Stringable|string $serviceKey
     *
     * @return bool
     */
    public function isCircuitOpen(
        Stringable|string $serviceKey
    ): bool {
        return (bool) $this->redis->exists($this->getCacheStatusKey($serviceKey));
    }

    /**
     * Verifica se algum circuito do recurso está aberto.
     *
     * @param CircuitBreakResource|string $circuitBreakResource
     *
     * @return bool
     */
    public function isResourceCircuitOpen (
        CircuitBreakResource|string $circuitBreakResource
    ): bool {
        foreach ($this->getResourceCircuitBreakers($circuitBreakResource) as $circuitBreak) {
            if ($this->isCircuitOpen($circuitBreak)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna o status do circuit breaker.
     *
     * @param Stringable|string $serviceKey
     *
     * @return CircuitBreakStatus
     */
    public function getCircuitBreakStatus(
        Stringable|string $serviceKey
    ): CircuitBreakStatus {
        return new CircuitBreakStatus(
            $serviceKey,
            $this->isCircuitOpen($serviceKey),
            (int) $this->redis->get(
                $this->getCacheFailureKey($serviceKey)
            ) ?? 0,
            $this->getCircuitBreakerRemainingOpenTime($serviceKey)
        );
    }

    /**
     * @param Stringable|string $serviceKey
     *
     * @return int
     */
    public function getCircuitBreakerRemainingOpenTime(
        Stringable|string $serviceKey
    ): int {
        return max(
            $this->redis->ttl(
                $this->getCacheStatusKey($serviceKey)
            ),
            0
        );
    }

    /**
     * Incrementa erro para o recurso definido.
     *
     * @param CircuitBreakResource|string $resource
     *
     * @return void
     */
    public function recordResourceFailure(
        CircuitBreakResource|string $resource
    ): void {
        foreach ($this->getResourceCircuitBreakers($resource) as $circuitBreak) {
            $this->recordCircuitBreakFailure($circuitBreak);
        }
    }

    /**
     * Incrementa erro para o circuito e caso atinja o threshold abre o mesmo.
     *
     * @param Stringable|string $serviceKey
     *
     * @return CircuitBreakStatus
     */
    public function recordCircuitBreakFailure(
        Stringable|string $serviceKey
    ): CircuitBreakStatus {
        $circuitBreak = $this->getCircuitBreak($serviceKey);
        $key = $this->getCacheFailureKey($serviceKey);
        $failures = $this->redis->incr($key);
        $this->redis->expire($key, $circuitBreak->getConfig()->getTimeout());
        if ($failures >= $circuitBreak->getConfig()->getThreshold()) {
            $this->openCircuit($circuitBreak);
        }
        return $this->getCircuitBreakStatus($circuitBreak);
    }

    /**
     * Abre o circuito.
     *
     * @param Stringable|string $serviceKey
     * @param int|null          $timeout
     *
     * @return void
     */
    public function openCircuit(
        Stringable|string $serviceKey,
        ?int $timeout = null
    ): void {
        $this->redis->setex(
            $this->getCacheStatusKey($serviceKey),
            $timeout ?? $this->getCircuitBreak($serviceKey)->getConfig()->getTimeout(),
            '1'
        );
        $this->logger->error(
            sprintf(
                'Circuit breaker aberto para o service key "%s".',
                $serviceKey
            )
        );
    }

    /**
     * Reseta os erros do circuito fechando o mesmo.
     *
     * @param Stringable|string $serviceKey
     *
     * @return void
     */
    public function resetCircuitBreakFailures(
        Stringable|string $serviceKey
    ): void {
        $this->redis->del($this->getCacheFailureKey($serviceKey));
        $this->redis->del($this->getCacheStatusKey($serviceKey));
    }

    /**
     * Fecha o circuito.
     *
     * @param Stringable|string $serviceKey
     *
     * @return void
     */
    public function closeCircuit (
        Stringable|string $serviceKey
    ): void {
        $this->resetCircuitBreakFailures($serviceKey);
    }

    /**
     * Fecha o circuito para o recurso especificado.
     *
     * @param CircuitBreakResource|string $resource
     *
     * @return void
     */
    public function closeResourceCircuits(
        CircuitBreakResource|string $resource
    ): void {
        $resource = is_string($resource) ? $this->getResource($resource) : $resource;
        foreach ($resource->getServicesKeys() as $serviceKey) {
            $this->resetCircuitBreakFailures($serviceKey);
        }
    }

    /**
     * Fecha todos os circuitos.
     *
     * @return void
     */
    public function closeAllCircuits(): void
    {
        foreach ($this->circuitBreakersResource as $resource) {
            foreach ($resource->getServicesKeys() as $serviceKey) {
                $this->closeCircuit($serviceKey);
            }
        }
    }

    /**
     * Abre todos os circuitos.
     *
     * @param int|null $timeout
     *
     * @return void
     */
    public function openAllCircuits(
        ?int $timeout = null
    ): void {
        foreach ($this->circuitBreakersResource as $resource) {
            foreach ($resource->getServicesKeys() as $serviceKey) {
                $this->openCircuit($serviceKey, $timeout);
            }
        }
    }

    /**
     * Trata a exception no fluxo do circuit breaker.
     *
     * @param Throwable $e
     *
     * @return Throwable|null
     */
    public function handleException(
        Throwable $e
    ): ?Throwable {
        if ($e instanceof CircuitBreakerOpenException) {
            return $e;
        }
        $exceptionClass = get_class($e);
        $statusCode = $e->getCode();
        /** @var CircuitBreakStatus $lastOpenStatus */
        $lastOpenStatus = null;
        foreach ($this->circuitBreakers as $circuitBreak) {
            if (!$circuitBreak->hasConfig()) {
                continue;
            }
            $exceptions = $circuitBreak->getConfig()->getExceptions();
            if (in_array('*', $exceptions)
                || in_array($exceptionClass, $exceptions)
                || in_array($statusCode, $exceptions)) {
                $status = $this->recordCircuitBreakFailure($circuitBreak);
                if ($status->isOpen()) {
                    if ($status->getRemainingOpenTime() > $lastOpenStatus?->getRemainingOpenTime()) {
                        $lastOpenStatus = $status;
                    }
                }
            }
        }

        if ($lastOpenStatus) {
            return new CircuitBreakerOpenException(
                $lastOpenStatus->getServiceKey(),
                $lastOpenStatus->getRemainingOpenTime(),
                $e
            );
        }
        return null;
    }

    /**
     * @param Stringable|string $serviceKey
     *
     * @return CircuitBreak
     */
    public function getCircuitBreak(
        Stringable|string $serviceKey
    ): CircuitBreak {
        if ($serviceKey instanceof CircuitBreak) {
            return $serviceKey;
        }
        if (isset($this->circuitBreakers[(string) $serviceKey])) {
            return $this->circuitBreakers[(string) $serviceKey];
        }
        throw new NotFoundServiceKeyException((string) $serviceKey);
    }

    /**
     * @param string $serviceKey
     *
     * @return bool
     */
    public function isCircuitBreakRegistered(
        string $serviceKey
    ): bool {
        return isset($this->circuitBreakers[$serviceKey]);
    }

    /**
     * @return CircuitBreak[]
     */
    public function getCircuitBreakers(): array
    {
        return $this->circuitBreakers;
    }

    /**
     * @param CircuitBreakResource|string $resource
     *
     * @return CircuitBreak[]
     */
    public function getResourceCircuitBreakers(
        CircuitBreakResource|string $resource
    ): array {
        $resource = is_string($resource) ? $this->getResource($resource) : $resource;
        $circuitBreakers = [];
        if ($resource) {
            foreach ($resource->getServicesKeys() as $serviceKey) {
                if (!$this->isCircuitBreakRegistered($serviceKey)) {
                    continue;
                }
                $circuitBreakers[] = $this->getCircuitBreak($serviceKey);
            }
        }

        return $circuitBreakers;
    }

    /**
     * @param string $resource
     *
     * @return CircuitBreakResource|null
     */
    public function getResource(
        string $resource
    ): ?CircuitBreakResource {
        if (isset($this->circuitBreakersResource[$resource])) {
            return $this->circuitBreakersResource[$resource];
        }
        return null;
    }

    /**
     * @return CircuitBreakResource[]
     */
    public function getResources(): array {
        return $this->circuitBreakersResource;
    }

    /**
     * @param CircuitBreak $circuitBreak
     *
     * @return CircuitBreak
     */
    public function registerCircuitBreak(
        CircuitBreak $circuitBreak
    ): CircuitBreak {
        if (!isset($this->circuitBreakers[$circuitBreak->getServiceKey()])) {
            $this->circuitBreakers[$circuitBreak->getServiceKey()] = $circuitBreak;
        }

        return $this->circuitBreakers[$circuitBreak->getServiceKey()];
    }

    /**
     * @param string     $serviceKey
     * @param int|null   $timeout
     * @param int|null   $threshold
     * @param array|null $exceptions
     *
     * @return CircuitBreak
     */
    public function buildAndRegisterCircuitBreak(
        string $serviceKey,
        ?int $timeout = null,
        ?int $threshold = null,
        ?array $exceptions = null
    ): CircuitBreak {
        $createConfig = isset($timeout, $threshold, $exceptions);
        $circuitBreak = new CircuitBreak(
            $serviceKey,
            $createConfig ?
                new CircuitBreakConfig(
                    $timeout,
                    $threshold,
                    $exceptions
                ) :
                null
        );
        $this->registerCircuitBreak($circuitBreak);

        return $circuitBreak;
    }

    /**
     * @param string $resourceName
     * @param array  $serviceKeys
     *
     * @return CircuitBreakResource
     */
    public function buildAndRegisterResource(
        string $resourceName,
        array $serviceKeys
    ): CircuitBreakResource {
        $resource = new CircuitBreakResource(
            $resourceName,
            $serviceKeys
        );
        $this->registerResource($resource);

        return $resource;
    }

    /**
     * @param CircuitBreakResource $resource
     *
     * @return void
     */
    public function registerResource(
        CircuitBreakResource $resource
    ): void {
        $this->circuitBreakersResource[$resource->getResourceName()] = $resource;
    }
}
