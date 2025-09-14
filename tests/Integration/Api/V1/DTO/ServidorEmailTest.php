<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ServidorEmailTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ServidorEmail as ServidorEmailDto;
use SuppCore\AdministrativoBackend\Entity\ServidorEmail as ServidorEmailEntity;

/**
 * Class ServidorEmailTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ServidorEmailTest extends DtoTestCase
{
    protected string $dtoClass = ServidorEmailDto::class;

    protected string $entityClass = ServidorEmailEntity::class;
}
