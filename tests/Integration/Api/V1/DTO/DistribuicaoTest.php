<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DistribuicaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Distribuicao as DistribuicaoDto;
use SuppCore\AdministrativoBackend\Entity\Distribuicao as DistribuicaoEntity;

/**
 * Class DistribuicaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuicaoTest extends DtoTestCase
{
    protected string $dtoClass = DistribuicaoDto::class;

    protected string $entityClass = DistribuicaoEntity::class;
}
