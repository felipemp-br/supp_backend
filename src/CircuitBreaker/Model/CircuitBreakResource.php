<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

use Attribute;

/**
 * CircuitBreakResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class CircuitBreakResource
{
    /**
     * Constructor.
     *
     * @param string $resourceName
     * @param array  $servicesKeys
     */
    public function __construct(
        private string $resourceName,
        private array $servicesKeys
    ) {
    }

    /**
     * Return resouceName.
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * Return servicesKeys.
     *
     * @return array
     */
    public function getServicesKeys(): array
    {
        return $this->servicesKeys;
    }
}
