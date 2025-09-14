<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

/**
 * CircuitBreakStatus.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CircuitBreakStatus
{
    /**
     * Constructor.
     *
     * @param string $serviceKey
     * @param bool   $open
     * @param int    $failuresCount
     * @param int    $remainingOpenTime
     */
    public function __construct(
        private string $serviceKey,
        private bool $open,
        private int $failuresCount,
        private int $remainingOpenTime = 0,
    ) {
    }

    /**
     * Return open.
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->open;
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

    /**
     * Return failuresCount.
     *
     * @return int
     */
    public function getFailuresCount(): int
    {
        return $this->failuresCount;
    }

    /**
     * Return remainingTime.
     *
     * @return int
     */
    public function getRemainingOpenTime(): int
    {
        return $this->remainingOpenTime;
    }

    /**
     * Set open.
     *
     * @param bool $open
     *
     * @return CircuitBreakStatus
     */
    public function setOpen(bool $open): CircuitBreakStatus
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Set failuresCount.
     *
     * @param int $failuresCount
     *
     * @return CircuitBreakStatus
     */
    public function setFailuresCount(int $failuresCount): CircuitBreakStatus
    {
        $this->failuresCount = $failuresCount;

        return $this;
    }

    /**
     * Set remainingOpenTime.
     *
     * @param int $remainingOpenTime
     *
     * @return CircuitBreakStatus
     */
    public function setRemainingOpenTime(int $remainingOpenTime): CircuitBreakStatus
    {
        $this->remainingOpenTime = $remainingOpenTime;

        return $this;
    }
}
