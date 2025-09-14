<?php

declare(strict_types=1);
/**
 * /src/Rest/SearchTermInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

/**
 * Interface SearchTermInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface SearchTermInterface
{
    // Used OPERAND constants
    public const OPERAND_OR = 'or';
    public const OPERAND_AND = 'and';

    // Used MODE constants
    public const MODE_STARTS_WITH = 1;
    public const MODE_ENDS_WITH = 2;
    public const MODE_FULL = 3;
}
