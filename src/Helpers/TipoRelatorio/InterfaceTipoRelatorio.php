<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\TipoRelatorio;

use stdClass;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;

/**
 *
 */
interface InterfaceTipoRelatorio
{
    public function supports(TipoRelatorio $tipoRelatorio): bool;

    public function getArrayResult(TipoRelatorio $tipoRelatorio, array &$parametros): array;
}
