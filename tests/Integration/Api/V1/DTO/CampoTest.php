<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/CampoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Campo as CampoDto;
use SuppCore\AdministrativoBackend\Entity\Campo as CampoEntity;

/**
 * Class CampoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CampoTest extends DtoTestCase
{
    protected string $dtoClass = CampoDto::class;

    protected string $entityClass = CampoEntity::class;
}
