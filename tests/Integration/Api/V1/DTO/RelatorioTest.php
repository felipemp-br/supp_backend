<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RelatorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio as RelatorioDto;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;

/**
 * Class RelatorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelatorioTest extends DtoTestCase
{
    protected string $dtoClass = RelatorioDto::class;

    protected string $entityClass = RelatorioEntity::class;
}
