<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * UnauthorizedDocumentAccessException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnauthorizedDocumentAccessException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param int $documentoId
     */
    public function __construct(
        int $documentoId
    ) {
        parent::__construct(
            'Não é permitido o uso de documentos com restrição de acesso!',
            403
        );
    }
}
