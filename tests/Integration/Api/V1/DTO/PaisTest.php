<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/PaisTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Pais as PaisDto;
use SuppCore\AdministrativoBackend\Entity\Pais as PaisEntity;

/**
 * Class PaisTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PaisTest extends DtoTestCase
{
    protected string $dtoClass = PaisDto::class;

    protected string $entityClass = PaisEntity::class;
}
