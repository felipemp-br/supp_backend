<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Exceptions;

use Exception;

/**
 * SelectorNotFoundException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SelectorNotFoundException extends Exception
{
    public function __construct(string $expression)
    {
        parent::__construct(
            sprintf(
                'Não foi encontrado seletor de documento de regras de etiqueta para a expressão  [%s].',
                $expression
            ),
            400
        );
    }
}
