<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces;

use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreakResource;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * RegisterResourceInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

#[AutoconfigureTag(self::REGISTER_RESOURCE_TAG)]
interface RegisterResourceInterface
{
    public const string REGISTER_RESOURCE_TAG = 'supp_core.administrativo_backend.circuit_breaker.resource';

    /**
     * Retorna a configuração de registro do resource para ser
     * observado pelos listeners (controller, command e messenger).
     *
     * @return CircuitBreakResource
     */
    public function getCircuitBreakerResource(): CircuitBreakResource;
}
