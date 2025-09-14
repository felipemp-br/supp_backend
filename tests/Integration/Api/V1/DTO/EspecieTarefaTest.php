<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieTarefaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieTarefa as EspecieTarefaDto;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa as EspecieTarefaEntity;

/**
 * Class EspecieTarefaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieTarefaTest extends DtoTestCase
{
    protected string $dtoClass = EspecieTarefaDto::class;

    protected string $entityClass = EspecieTarefaEntity::class;
}
