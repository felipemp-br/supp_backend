<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieRelatorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieRelatorio as EspecieRelatorioDto;
use SuppCore\AdministrativoBackend\Entity\EspecieRelatorio as EspecieRelatorioEntity;

/**
 * Class EspecieRelatorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieRelatorioTest extends DtoTestCase
{
    protected string $dtoClass = EspecieRelatorioDto::class;

    protected string $entityClass = EspecieRelatorioEntity::class;
}
