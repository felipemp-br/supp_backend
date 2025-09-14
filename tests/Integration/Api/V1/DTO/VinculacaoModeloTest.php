<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoModeloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;

/**
 * Class VinculacaoModeloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoModeloTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoModeloDto::class;

    protected string $entityClass = VinculacaoModeloEntity::class;
}
