<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroRelatorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelatorio as GeneroRelatorioDto;
use SuppCore\AdministrativoBackend\Entity\GeneroRelatorio as GeneroRelatorioEntity;

/**
 * Class GeneroRelatorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroRelatorioTest extends DtoTestCase
{
    protected string $dtoClass = GeneroRelatorioDto::class;

    protected string $entityClass = GeneroRelatorioEntity::class;
}
