<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoTeseTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoTese as VinculacaoTeseDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoTese as VinculacaoTeseEntity;

/**
 * Class VinculacaoTeseTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoTeseTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoTeseDto::class;

    protected string $entityClass = VinculacaoTeseEntity::class;
}
