<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ParametroAdministrativoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo as ParametroAdministrativoDto;
use SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo as ParametroAdministrativoEntity;

/**
 * Class ParametroAdministrativoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ParametroAdministrativoTest extends DtoTestCase
{
    protected string $dtoClass = ParametroAdministrativoDto::class;

    protected string $entityClass = ParametroAdministrativoEntity::class;
}
