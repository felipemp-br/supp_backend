<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Complex\Exception;
use Throwable;

/**
 * InteligenciaArtificalException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteligenciaArtificalException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            'Erro durante o processamento da inteligência artificial.',
            500,
            $previous
        );
    }
}
