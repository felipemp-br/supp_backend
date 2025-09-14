<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ContatoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Contato as ContatoDto;
use SuppCore\AdministrativoBackend\Entity\Contato as ContatoEntity;

/**
 * Class ContatoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ContatoTest extends DtoTestCase
{
    protected string $dtoClass = ContatoDto::class;

    protected string $entityClass = ContatoEntity::class;
}
