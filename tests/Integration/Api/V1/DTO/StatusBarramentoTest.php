<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/StatusBarramentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\StatusBarramento as StatusBarramentoDto;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento as StatusBarramentoEntity;

/**
 * Class StatusBarramentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class StatusBarramentoTest extends DtoTestCase
{
    protected string $dtoClass = StatusBarramentoDto::class;

    protected string $entityClass = StatusBarramentoEntity::class;
}
