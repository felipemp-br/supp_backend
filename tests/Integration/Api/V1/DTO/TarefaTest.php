<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TarefaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDto;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;

/**
 * Class TarefaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TarefaTest extends DtoTestCase
{
    protected string $dtoClass = TarefaDto::class;

    protected string $entityClass = TarefaEntity::class;
}
