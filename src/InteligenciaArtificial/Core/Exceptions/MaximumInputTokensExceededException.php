<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * MaximumInputTokensExceededException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MaximumInputTokensExceededException extends Exception implements InteligenciaArtificalExceptionInterface
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
                'Excedido o tamanho máximo da janela de contexto usando o driver %s.',
                $driver
            ),
            400,
            $e
        );
    }
}
