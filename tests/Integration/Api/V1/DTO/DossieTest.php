<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DossieTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDto;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;

/**
 * Class DossieTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DossieTest extends DtoTestCase
{
    protected string $dtoClass = DossieDto::class;

    protected string $entityClass = DossieEntity::class;
}
