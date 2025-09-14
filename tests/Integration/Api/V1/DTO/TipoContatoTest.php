<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoContatoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoContato as TipoContatoDto;
use SuppCore\AdministrativoBackend\Entity\TipoContato as TipoContatoEntity;

/**
 * Class TipoContatoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoContatoTest extends DtoTestCase
{
    protected string $dtoClass = TipoContatoDto::class;

    protected string $entityClass = TipoContatoEntity::class;
}
