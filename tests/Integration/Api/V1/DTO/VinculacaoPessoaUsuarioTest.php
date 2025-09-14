<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoPessoaUsuarioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario as VinculacaoPessoaUsuarioEntity;

/**
 * Class VinculacaoPessoaUsuarioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoPessoaUsuarioTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoPessoaUsuarioDto::class;

    protected string $entityClass = VinculacaoPessoaUsuarioEntity::class;
}
