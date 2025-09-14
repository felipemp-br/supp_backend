<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/CronjobTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as CronjobDto;
use SuppCore\AdministrativoBackend\Entity\Cronjob as CronjobEntity;

/**
 * Class CronjobTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobTest extends DtoTestCase
{
    protected string $dtoClass = CronjobDto::class;

    protected string $entityClass = CronjobEntity::class;
}
