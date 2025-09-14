<?php

declare(strict_types=1);
/**
 * /src/Enums/Traits/EnumsToArray.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Enums\Traits;

use BackedEnum;

/**
 * Class EnumsToArray.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait EnumsToArray
{
    public static function enumValues(): array
    {
        return array_map(static fn (BackedEnum $item): string|int => $item->value, self::cases());
    }

    public static function enumNames(): array
    {
        return array_map(static fn (BackedEnum $item): string => $item->name, self::cases());
    }
}
