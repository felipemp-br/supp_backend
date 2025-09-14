<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/MunicipioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio as MunicipioDto;
use SuppCore\AdministrativoBackend\Entity\Municipio as MunicipioEntity;

/**
 * Class MunicipioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MunicipioTest extends DtoTestCase
{
    protected string $dtoClass = MunicipioDto::class;

    protected string $entityClass = MunicipioEntity::class;
}
