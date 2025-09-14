<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions;

use Exception;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;

/**
 * UnsupportedComponenteDigitalMimeTypeException.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnsupportedComponenteDigitalMimeTypeException extends Exception implements InteligenciaArtificalExceptionInterface
{
    /**
     * Constructor.
     *
     * @param ComponenteDigital $componenteDigital
     *
     */
    public function __construct(ComponenteDigital $componenteDigital)
    {
        parent::__construct(
            sprintf(
                'MimeType %s do Componente Digital id %s não suportado.',
                $componenteDigital->getMimetype(),
                $componenteDigital->getId()
            ),
            400
        );
    }

}
