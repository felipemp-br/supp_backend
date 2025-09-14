<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ConfiguracaoNupTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfiguracaoNup as ConfiguracaoNupDto;
use SuppCore\AdministrativoBackend\Entity\ConfiguracaoNup as ConfiguracaoNupEntity;

/**
 * Class ConfiguracaoNupTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfiguracaoNupTest extends DtoTestCase
{
    protected string $dtoClass = ConfiguracaoNupDto::class;

    protected string $entityClass = ConfiguracaoNupEntity::class;
}
