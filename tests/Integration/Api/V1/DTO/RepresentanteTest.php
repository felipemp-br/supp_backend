<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RepresentanteTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Representante as RepresentanteDto;
use SuppCore\AdministrativoBackend\Entity\Representante as RepresentanteEntity;

/**
 * Class RepresentanteTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RepresentanteTest extends DtoTestCase
{
    protected string $dtoClass = RepresentanteDto::class;

    protected string $entityClass = RepresentanteEntity::class;
}
