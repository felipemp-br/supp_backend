<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EstadoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Estado as EstadoDto;
use SuppCore\AdministrativoBackend\Entity\Estado as EstadoEntity;

/**
 * Class EstadoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EstadoTest extends DtoTestCase
{
    protected string $dtoClass = EstadoDto::class;

    protected string $entityClass = EstadoEntity::class;
}
