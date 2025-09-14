<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/UrnTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Urn as UrnDto;
use SuppCore\AdministrativoBackend\Entity\Urn as UrnEntity;

/**
 * Class UrnTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UrnTest extends DtoTestCase
{
    protected string $dtoClass = UrnDto::class;

    protected string $entityClass = UrnEntity::class;
}
