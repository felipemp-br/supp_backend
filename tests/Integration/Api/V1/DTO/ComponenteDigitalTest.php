<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ComponenteDigitalTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDto;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;

/**
 * Class ComponenteDigitalTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ComponenteDigitalTest extends DtoTestCase
{
    protected string $dtoClass = ComponenteDigitalDto::class;

    protected string $entityClass = ComponenteDigitalEntity::class;
}
