<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DominioAdministrativoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DominioAdministrativo as DominioAdministrativoDto;
use SuppCore\AdministrativoBackend\Entity\DominioAdministrativo as DominioAdministrativoEntity;

/**
 * Class DominioAdministrativoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DominioAdministrativoTest extends DtoTestCase
{
    protected string $dtoClass = DominioAdministrativoDto::class;

    protected string $entityClass = DominioAdministrativoEntity::class;
}
