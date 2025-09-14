<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

use Attribute;
use Stringable;
use SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions\EmptyCircuitBreakConfigException;

/**
 * CircuitBreak.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class CircuitBreak implements Stringable
{
    /**
     * Constructor.
     *
     * @param string                  $serviceKey
     * @param CircuitBreakConfig|null $config
     */
    public function __construct(
        private readonly string $serviceKey,
        private ?CircuitBreakConfig $config = null,
    ) {
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
     * Return config.
     *
     * @return CircuitBreakConfig|null
     */
    public function getConfig(): ?CircuitBreakConfig
    {
        if (!$this->config) {
            throw new EmptyCircuitBreakConfigException($this->serviceKey);
        }

        return $this->config;
    }

    /**
     * @return bool
     */
    public function hasConfig(): bool
    {
        return $this->config ? true : false;
    }

    /**
     * Set config.
     *
     * @param CircuitBreakConfig $config
     *
     * @return CircuitBreak
     */
    public function setConfig(CircuitBreakConfig $config): CircuitBreak
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getServiceKey();
    }
}
