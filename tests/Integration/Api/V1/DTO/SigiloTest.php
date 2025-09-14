<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/SigiloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo as SigiloDto;
use SuppCore\AdministrativoBackend\Entity\Sigilo as SigiloEntity;

/**
 * Class SigiloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SigiloTest extends DtoTestCase
{
    protected string $dtoClass = SigiloDto::class;

    protected string $entityClass = SigiloEntity::class;
}
