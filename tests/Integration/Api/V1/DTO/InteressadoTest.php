<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/InteressadoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDto;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;

/**
 * Class InteressadoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InteressadoTest extends DtoTestCase
{
    protected string $dtoClass = InteressadoDto::class;

    protected string $entityClass = InteressadoEntity::class;
}
