<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoMetadadosTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoMetadados as VinculacaoMetadadosDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoMetadados as VinculacaoMetadadosEntity;

/**
 * Class VinculacaoMetadadosTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoMetadadosTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoMetadadosDto::class;

    protected string $entityClass = VinculacaoMetadadosEntity::class;
}
