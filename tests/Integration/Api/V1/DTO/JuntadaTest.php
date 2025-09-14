<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/JuntadaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;

/**
 * Class JuntadaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JuntadaTest extends DtoTestCase
{
    protected string $dtoClass = JuntadaDto::class;

    protected string $entityClass = JuntadaEntity::class;
}
