<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DadosFormularioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDto;
use SuppCore\AdministrativoBackend\Entity\DadosFormulario as DadosFormularioEntity;

/**
 * Class DadosFormularioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DadosFormularioTest extends DtoTestCase
{
    protected string $dtoClass = DadosFormularioDto::class;

    protected string $entityClass = DadosFormularioEntity::class;
}
