<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TeseTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tese as TeseDto;
use SuppCore\AdministrativoBackend\Entity\Tese as TeseEntity;

/**
 * Class TemaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TeseTest extends DtoTestCase
{
    protected string $dtoClass = TeseDto::class;

    protected string $entityClass = TeseEntity::class;
}
