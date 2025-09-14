<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoRelatorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoRelatorio as TipoRelatorioDto;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio as TipoRelatorioEntity;

/**
 * Class TipoRelatorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoRelatorioTest extends DtoTestCase
{
    protected string $dtoClass = TipoRelatorioDto::class;

    protected string $entityClass = TipoRelatorioEntity::class;
}
