<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoRoleTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRole as VinculacaoRoleDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRole as VinculacaoRoleEntity;

/**
 * Class VinculacaoRoleTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoRoleTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoRoleDto::class;

    protected string $entityClass = VinculacaoRoleEntity::class;
}
