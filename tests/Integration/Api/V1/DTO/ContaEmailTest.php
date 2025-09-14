<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ContaEmailTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ContaEmail as ContaEmailDto;
use SuppCore\AdministrativoBackend\Entity\ContaEmail as ContaEmailEntity;

/**
 * Class ContaEmailTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ContaEmailTest extends DtoTestCase
{
    protected string $dtoClass = ContaEmailDto::class;

    protected string $entityClass = ContaEmailEntity::class;
}
