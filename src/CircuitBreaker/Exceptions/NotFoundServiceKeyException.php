<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Exceptions;

use InvalidArgumentException;

/**
 * NotFoundServiceKeyException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NotFoundServiceKeyException extends InvalidArgumentException
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
                'O circuit breaker não conseguiu encontrar as configurações para a service key %s',
                $serviceKey
            ),
            400,
        );
    }
}
