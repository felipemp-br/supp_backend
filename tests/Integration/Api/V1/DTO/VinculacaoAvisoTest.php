<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoAvisoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoAviso as VinculacaoAvisoDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoAviso as VinculacaoAvisoEntity;

/**
 * Class VinculacaoAvisoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoAvisoTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoAvisoDto::class;

    protected string $entityClass = VinculacaoAvisoEntity::class;
}
