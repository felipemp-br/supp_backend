<?php

declare(strict_types=1);
/**
 * /src/MapperPipes/OverridePipeInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper\Pipes;

/**
 * Interface OverridePipeInterface.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface OverridePipeInterface
{
    public function overridePipes(): array;
}
