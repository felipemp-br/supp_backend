<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TransicaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao as TransicaoDto;
use SuppCore\AdministrativoBackend\Entity\Transicao as TransicaoEntity;

/**
 * Class TransicaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransicaoTest extends DtoTestCase
{
    protected string $dtoClass = TransicaoDto::class;

    protected string $entityClass = TransicaoEntity::class;
}
