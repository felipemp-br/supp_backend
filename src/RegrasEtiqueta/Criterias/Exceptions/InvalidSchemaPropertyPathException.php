<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions;

use Exception;

/**
 * InvalidSchemaPropertyPathException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InvalidSchemaPropertyPathException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $property
     */
    public function __construct(string $property)
    {
        parent::__construct(
            sprintf(
                'Não foi encontrada a propriedade [%s] no JSON Schema definido.',
                $property
            ),
            400
        );
    }
}
