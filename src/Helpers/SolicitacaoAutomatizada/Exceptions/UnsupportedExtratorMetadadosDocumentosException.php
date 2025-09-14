<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions;

use Exception;
use Throwable;

/**
 * UnsupportedExtratorMetadadosDocumentosException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnsupportedExtratorMetadadosDocumentosException extends Exception implements SolicitacaoAutomatizadaExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string         $type
     * @param Throwable|null $previous
     */
    public function __construct(
        string $type,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf('Extrator de metadados de documentos %s não suportado.', $type), 400, $previous);
    }
}
