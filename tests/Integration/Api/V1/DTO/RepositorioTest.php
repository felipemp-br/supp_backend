<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RepositorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio as RepositorioDto;
use SuppCore\AdministrativoBackend\Entity\Repositorio as RepositorioEntity;

/**
 * Class RepositorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RepositorioTest extends DtoTestCase
{
    protected string $dtoClass = RepositorioDto::class;

    protected string $entityClass = RepositorioEntity::class;
}
