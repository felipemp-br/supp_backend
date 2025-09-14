<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AvaliacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao as AvaliacaoDto;
use SuppCore\AdministrativoBackend\Entity\Avaliacao as AvaliacaoEntity;

/**
 * Class AvaliacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AvaliacaoTest extends DtoTestCase
{
    protected string $dtoClass = AvaliacaoDto::class;

    protected string $entityClass = AvaliacaoEntity::class;
}
