<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AcaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Acao as AcaoDto;
use SuppCore\AdministrativoBackend\Entity\Acao as AcaoEntity;

/**
 * Class AcaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcaoTest extends DtoTestCase
{
    protected string $dtoClass = AcaoDto::class;

    protected string $entityClass = AcaoEntity::class;
}
