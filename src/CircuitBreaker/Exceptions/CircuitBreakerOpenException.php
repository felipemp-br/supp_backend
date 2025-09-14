<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions;

use Exception;
use Throwable;

/**
 * CircuitBreakerOpenException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CircuitBreakerOpenException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $serviceKey
     * @param int    $remainingOpenTime
     *
     * @param Throwable|null $previous
     */
    public function __construct(
        protected string $serviceKey,
        protected int $remainingOpenTime,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Recurso bloqueado pelo circuit breaker.Tempo para fechamento do circuito: %s segundo(s).',
                $this->remainingOpenTime
            ),
            429,
            $previous
        );
    }

    /**
     * Return remainingOpenTime.
     *
     * @return int
     */
    public function getRemainingOpenTime(): int
    {
        return $this->remainingOpenTime;
    }

    /**
     * Return serviceKey.
     *
     * @return string
     */
    public function getServiceKey(): string
    {
        return $this->serviceKey;
    }
}
