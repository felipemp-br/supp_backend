<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoSigiloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoSigilo as TipoSigiloDto;
use SuppCore\AdministrativoBackend\Entity\TipoSigilo as TipoSigiloEntity;

/**
 * Class TipoSigiloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoSigiloTest extends DtoTestCase
{
    protected string $dtoClass = TipoSigiloDto::class;

    protected string $entityClass = TipoSigiloEntity::class;
}
