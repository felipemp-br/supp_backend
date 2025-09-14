<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieProcessoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDto;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;

/**
 * Class EspecieProcessoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieProcessoTest extends DtoTestCase
{
    protected string $dtoClass = EspecieProcessoDto::class;

    protected string $entityClass = EspecieProcessoEntity::class;
}
