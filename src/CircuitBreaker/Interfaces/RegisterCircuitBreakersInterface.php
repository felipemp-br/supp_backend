<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Interfaces;

use SuppCore\AdministrativoBackend\CircuitBreaker\Model\CircuitBreak;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * RegisterCircuitBreakersInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

#[AutoconfigureTag(self::REGISTER_CIRCUIT_BREAKERS_TAG)]
interface RegisterCircuitBreakersInterface
{
    public const string REGISTER_CIRCUIT_BREAKERS_TAG = 'supp_core.administrativo_backend.circuit_breaker.circuit_breakers';

    /**
     * Retorna os circuit breakers para o recurso.
     *
     * @return CircuitBreak[]
     */
    public function getCircuitBreakers(): array;
}
