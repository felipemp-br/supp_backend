<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/UsuarioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDto;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;

/**
 * Class UsuarioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UsuarioTest extends DtoTestCase
{
    protected string $dtoClass = UsuarioDto::class;

    protected string $entityClass = UsuarioEntity::class;
}
