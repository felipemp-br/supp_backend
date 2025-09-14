<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroAtividadeTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroAtividade as GeneroAtividadeDto;
use SuppCore\AdministrativoBackend\Entity\GeneroAtividade as GeneroAtividadeEntity;

/**
 * Class GeneroAtividadeTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroAtividadeTest extends DtoTestCase
{
    protected string $dtoClass = GeneroAtividadeDto::class;

    protected string $entityClass = GeneroAtividadeEntity::class;
}
