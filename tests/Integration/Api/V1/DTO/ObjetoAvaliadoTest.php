<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ObjetoAvaliadoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ObjetoAvaliado as ObjetoAvaliadoDto;
use SuppCore\AdministrativoBackend\Entity\ObjetoAvaliado as ObjetoAvaliadoEntity;

/**
 * Class ObjetoAvaliadoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ObjetoAvaliadoTest extends DtoTestCase
{
    protected string $dtoClass = ObjetoAvaliadoDto::class;

    protected string $entityClass = ObjetoAvaliadoEntity::class;
}
