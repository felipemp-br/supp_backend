<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * ClientAuthenticationException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClientAuthenticationException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string         $driver
     * @param Exception|null $e
     */
    public function __construct(
        string $driver,
        Exception $e = null
    ) {
        parent::__construct(
            sprintf(
                'Falha ao autenticar no client da inteligência artificial usando o driver %s.',
                $driver
            ),
            401,
            $e
        );
    }
}
