<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;

/**
 * ClientMissingConfigException.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClientMissingConfigException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string $clientName
     * @param string $paramName
     *
     */
    public function __construct(
        string $clientName,
        string $paramName
    ) {
        parent::__construct(
            sprintf(
                'Não foi possível criar o client %s, o paramêtro obrigatório [%s] não foi fornecido ou é inválido.',
                $clientName,
                $paramName
            ),
            500
        );
    }
}
