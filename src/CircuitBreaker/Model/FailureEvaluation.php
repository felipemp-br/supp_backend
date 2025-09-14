<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

/**
 * FailureEvaluation.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class FailureEvaluation
{
    public const bool DEFAULT_OPEN_CIRCUIT = false;
    public const int DEFAULT_TIMEOUT = 60;
    public const int DEFAULT_THRESHOLD = 1;

    /**
     * Constructor.
     *
     * @param bool $openCircuit
     * @param int  $timeout
     * @param int  $threshold
     */
    public function __construct(
        protected bool $openCircuit = self::DEFAULT_OPEN_CIRCUIT,
        protected int $timeout = self::DEFAULT_TIMEOUT,
        protected int $threshold = self::DEFAULT_THRESHOLD,
    ) {
    }

    /**
     * Return openNow.
     *
     * @return bool
     */
    public function mustOpenCircuit(): bool
    {
        return $this->openCircuit;
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
}
