<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/FeriadoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Feriado as FeriadoDto;
use SuppCore\AdministrativoBackend\Entity\Feriado as FeriadoEntity;

/**
 * Class FeriadoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FeriadoTest extends DtoTestCase
{
    protected string $dtoClass = FeriadoDto::class;

    protected string $entityClass = FeriadoEntity::class;
}
