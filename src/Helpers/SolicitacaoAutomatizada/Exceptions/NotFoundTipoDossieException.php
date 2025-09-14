<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * NotFoundTipoDossieException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NotFoundTipoDossieException extends Exception implements SolicitacaoAutomatizadaExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string         $sigla
     * @param Throwable|null $previous
     */
    public function __construct(
        string $sigla,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf('Tipo de dossie %s não encontrado.', $sigla), 400, $previous);
    }
}
