<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoDossieTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDossie as TipoDossieDto;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;

/**
 * Class TipoDossieTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDossieTest extends DtoTestCase
{
    protected string $dtoClass = TipoDossieDto::class;

    protected string $entityClass = TipoDossieEntity::class;
}
