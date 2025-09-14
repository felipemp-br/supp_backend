<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/LotacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDto;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;

/**
 * Class LotacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LotacaoTest extends DtoTestCase
{
    protected string $dtoClass = LotacaoDto::class;

    protected string $entityClass = LotacaoEntity::class;
}
