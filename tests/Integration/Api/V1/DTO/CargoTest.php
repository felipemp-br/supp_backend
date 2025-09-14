<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/CargoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Cargo as CargoDto;
use SuppCore\AdministrativoBackend\Entity\Cargo as CargoEntity;

/**
 * Class CargoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CargoTest extends DtoTestCase
{
    protected string $dtoClass = CargoDto::class;

    protected string $entityClass = CargoEntity::class;
}
