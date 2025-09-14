<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RamoDireitoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\RamoDireito as RamoDireitoDto;
use SuppCore\AdministrativoBackend\Entity\RamoDireito as RamoDireitoEntity;

/**
 * Class RamoDireitoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RamoDireitoTest extends DtoTestCase
{
    protected string $dtoClass = RamoDireitoDto::class;

    protected string $entityClass = RamoDireitoEntity::class;
}
