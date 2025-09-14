<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoParametroAdministrativoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoParametroAdministrativo as VinculacaoParametroAdministrativoDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoParametroAdministrativo as VinculacaoParametroAdministrativoEntity;

/**
 * Class VinculacaoParametroAdministrativoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoParametroAdministrativoTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoParametroAdministrativoDto::class;

    protected string $entityClass = VinculacaoParametroAdministrativoEntity::class;
}
