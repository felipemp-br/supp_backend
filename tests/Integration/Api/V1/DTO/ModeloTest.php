<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModeloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDto;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;

/**
 * Class ModeloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModeloTest extends DtoTestCase
{
    protected string $dtoClass = ModeloDto::class;

    protected string $entityClass = ModeloEntity::class;
}
