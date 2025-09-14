<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions;

use InvalidArgumentException;

/**
 * EmptyCircuitBreakConfigException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EmptyCircuitBreakConfigException extends InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param string $serviceKey
     */
    public function __construct(
        string $serviceKey,
    ) {
        parent::__construct(
            sprintf(
                'O circuit breaker não tem configurações definidas para o service key %s',
                $serviceKey
            ),
            400,
        );
    }
}
