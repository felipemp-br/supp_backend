<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoUsuarioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoUsuario as VinculacaoUsuarioDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario as VinculacaoUsuarioEntity;

/**
 * Class VinculacaoUsuarioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoUsuarioTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoUsuarioDto::class;

    protected string $entityClass = VinculacaoUsuarioEntity::class;
}
