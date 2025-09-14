<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * ExtracaoMetadadosErrorException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ExtracaoMetadadosErrorException extends Exception implements SolicitacaoAutomatizadaExceptionInterface
{
    /**
     * Constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(
        ?Throwable $previous = null
    ) {
        parent::__construct('Erro ao extrair metadados dos documentos.', 500, $previous);
    }
}
