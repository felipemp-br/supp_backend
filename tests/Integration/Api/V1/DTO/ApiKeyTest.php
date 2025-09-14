<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ApiKeyTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ApiKey as ApiKeyDto;
use SuppCore\AdministrativoBackend\Entity\ApiKey as ApiKeyEntity;

/**
 * Class ApiKeyTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ApiKeyTest extends DtoTestCase
{
    protected string $dtoClass = ApiKeyDto::class;

    protected string $entityClass = ApiKeyEntity::class;
}
