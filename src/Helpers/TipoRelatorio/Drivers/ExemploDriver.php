<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\TipoRelatorio\Drivers;

use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;
use SuppCore\AdministrativoBackend\Helpers\TipoRelatorio\InterfaceTipoRelatorio;

/**
 *
 */
class ExemploDriver implements InterfaceTipoRelatorio
{

    public const SUPPORTS = ['EXEMPLO'];

    public function supports(TipoRelatorio $tipoRelatorio): bool
    {
        if (in_array($tipoRelatorio->getEspecieRelatorio()->getNome(), self::SUPPORTS)) {
            return true;
        }

        return false;
    }

    public function getArrayResult(TipoRelatorio $tipoRelatorio, array &$parametros): array
    {
        return [];
    }
}
