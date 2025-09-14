<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AtividadeTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDto;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;

/**
 * Class AtividadeTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AtividadeTest extends DtoTestCase
{
    protected string $dtoClass = AtividadeDto::class;

    protected string $entityClass = AtividadeEntity::class;
}
