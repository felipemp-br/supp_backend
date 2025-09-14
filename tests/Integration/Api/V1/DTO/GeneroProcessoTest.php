<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroProcessoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroProcesso as GeneroProcessoDto;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso as GeneroProcessoEntity;

/**
 * Class GeneroProcessoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroProcessoTest extends DtoTestCase
{
    protected string $dtoClass = GeneroProcessoDto::class;

    protected string $entityClass = GeneroProcessoEntity::class;
}
