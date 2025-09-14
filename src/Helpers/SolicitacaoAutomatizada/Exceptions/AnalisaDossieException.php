<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * AnalisaDossieException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnalisaDossieException extends Exception implements SolicitacaoAutomatizadaExceptionInterface
{
    /**
     * Constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(
        ?Throwable $previous = null
    ) {
        parent::__construct('Erro ao analisar dossie(s).', 500, $previous);
    }
}
