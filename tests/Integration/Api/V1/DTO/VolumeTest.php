<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VolumeTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume as VolumeDto;
use SuppCore\AdministrativoBackend\Entity\Volume as VolumeEntity;

/**
 * Class VolumeTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VolumeTest extends DtoTestCase
{
    protected string $dtoClass = VolumeDto::class;

    protected string $entityClass = VolumeEntity::class;
}
