<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoSetorMunicipioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoSetorMunicipio as VinculacaoSetorMunicipioDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoSetorMunicipio as VinculacaoSetorMunicipioEntity;

/**
 * Class VinculacaoSetorMunicipioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoSetorMunicipioTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoSetorMunicipioDto::class;

    protected string $entityClass = VinculacaoSetorMunicipioEntity::class;
}
