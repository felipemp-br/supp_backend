<?php

declare(strict_types=1);
/**
 * /src/Enums/DossieVisibilidade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Enums;

use SuppCore\AdministrativoBackend\Enums\Traits\EnumsToArray;

/**
 * Class DossieVisibilidade
 */
enum DossieVisibilidade: int
{
    use EnumsToArray;

    case SEM_RESTRICAO = 0;
    case RESTRITO_SETOR = 1;
    case RESTRITO_UNIDADE = 2;
}
