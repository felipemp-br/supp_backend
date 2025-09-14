<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/SetorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;

/**
 * Class SetorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SetorTest extends DtoTestCase
{
    protected string $dtoClass = SetorDto::class;

    protected string $entityClass = SetorEntity::class;
}
