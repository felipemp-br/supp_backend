<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TramitacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDto;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;

/**
 * Class TramitacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TramitacaoTest extends DtoTestCase
{
    protected string $dtoClass = TramitacaoDto::class;

    protected string $entityClass = TramitacaoEntity::class;
}
