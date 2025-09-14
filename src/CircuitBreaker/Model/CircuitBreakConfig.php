<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

/**
 * CircuitBreakConfig.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CircuitBreakConfig
{
    /**
     * Constructor.
     *
     * @param int            $timeout
     * @param int            $threshold
     * @param string[]|int[] $exceptions
     */
    public function __construct(
        private int $timeout = 60,
        private int $threshold = 5,
        private array $exceptions = []
    ) {
    }

    /**
     * Return timeout.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Return threshold.
     *
     * @return int
     */
    public function getThreshold(): int
    {
        return $this->threshold;
    }

    /**
     * Return exceptions.
     *
     * @return array
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
