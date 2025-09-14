<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroSetorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroSetor as GeneroSetorDto;
use SuppCore\AdministrativoBackend\Entity\GeneroSetor as GeneroSetorEntity;

/**
 * Class GeneroSetorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroSetorTest extends DtoTestCase
{
    protected string $dtoClass = GeneroSetorDto::class;

    protected string $entityClass = GeneroSetorEntity::class;
}
