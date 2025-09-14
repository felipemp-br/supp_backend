<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * ClientRateLimitExeededException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClientRateLimitExeededException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string    $driver
     * @param Exception $e
     */
    public function __construct(
        string $driver,
        Exception $e,
        private readonly int $resetTimeout,
    ) {
        parent::__construct(
            sprintf(
                'Atingido o rate limit do client de inteligência artificial usando o driver %s.',
                $driver
            ),
            400,
            $e
        );
    }

    /**
     * Return resetTimeout (seconds).
     *
     * @return int
     */
    public function getResetTimeout(): int
    {
        return $this->resetTimeout;
    }
}
