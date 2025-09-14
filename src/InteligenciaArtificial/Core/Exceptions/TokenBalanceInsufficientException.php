<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * TokenBalanceInsufficientException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TokenBalanceInsufficientException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string    $driver
     * @param Exception $e
     */
    public function __construct(
        string $driver,
        Exception $e
    ) {
        parent::__construct(
            sprintf(
                'Quantidade de tokens disponíveis insuficiente usando o driver %s.',
                $driver
            ),
            400,
            $e
        );
    }
}
