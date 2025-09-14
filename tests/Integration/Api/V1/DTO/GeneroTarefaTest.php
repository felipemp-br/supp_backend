<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroTarefaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroTarefa as GeneroTarefaDto;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa as GeneroTarefaEntity;

/**
 * Class GeneroTarefaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroTarefaTest extends DtoTestCase
{
    protected string $dtoClass = GeneroTarefaDto::class;

    protected string $entityClass = GeneroTarefaEntity::class;
}
