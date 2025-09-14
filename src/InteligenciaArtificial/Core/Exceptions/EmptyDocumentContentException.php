<?php

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * EmptyDocumentContentException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EmptyDocumentContentException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param int $documentoId
     */
    public function __construct(
        int $documentoId,
    ) {
        parent::__construct(
            sprintf(
                'Conteúdo do documento %s vazio.',
                $documentoId
            ),
            400
        );
    }
}
