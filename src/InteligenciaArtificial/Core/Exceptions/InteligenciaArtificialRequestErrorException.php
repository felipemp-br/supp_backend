<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;
use Throwable;

/**
 * InteligenciaArtificialRequestErrorException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteligenciaArtificialRequestErrorException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string         $driver
     * @param Throwable|null $e
     */
    public function __construct(
        string $driver,
        Throwable $e = null
    ) {
        parent::__construct(
            sprintf(
                'Erro ao comunicar com a inteligência artificial usando o driver %s.',
                $driver
            ),
            $e?->getCode() ?? 500,
            $e
        );
    }
}
