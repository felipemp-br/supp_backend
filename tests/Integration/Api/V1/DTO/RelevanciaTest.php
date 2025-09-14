<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/RelevanciaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia as RelevanciaDto;
use SuppCore\AdministrativoBackend\Entity\Relevancia as RelevanciaEntity;

/**
 * Class RelevanciaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelevanciaTest extends DtoTestCase
{
    protected string $dtoClass = RelevanciaDto::class;

    protected string $entityClass = RelevanciaEntity::class;
}
