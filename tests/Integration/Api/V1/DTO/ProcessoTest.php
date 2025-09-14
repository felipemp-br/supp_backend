<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ProcessoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;

/**
 * Class ProcessoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessoTest extends DtoTestCase
{
    protected string $dtoClass = ProcessoDto::class;

    protected string $entityClass = ProcessoEntity::class;
}
