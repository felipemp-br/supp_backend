<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoOrgaoCentralMetadadosTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoOrgaoCentralMetadados as VinculacaoOrgaoCentralMetadadosDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoOrgaoCentralMetadados as VinculacaoOrgaoCentralMetadadosEntity;

/**
 * Class VinculacaoOrgaoCentralMetadadosTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoOrgaoCentralMetadadosTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoOrgaoCentralMetadadosDto::class;

    protected string $entityClass = VinculacaoOrgaoCentralMetadadosEntity::class;
}
