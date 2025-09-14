<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GrupoContatoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GrupoContato as GrupoContatoDto;
use SuppCore\AdministrativoBackend\Entity\GrupoContato as GrupoContatoEntity;

/**
 * Class GrupoContatoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GrupoContatoTest extends DtoTestCase
{
    protected string $dtoClass = GrupoContatoDto::class;

    protected string $entityClass = GrupoContatoEntity::class;
}
