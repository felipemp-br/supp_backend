<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;


use Exception;

/**
 * UnsupportedUriException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnsupportedUriException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('URI de configuração de inteligência artificial não suportada.', 400);
    }
}
