<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/EspecieRelevanciaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieRelevancia as EspecieRelevanciaDto;
use SuppCore\AdministrativoBackend\Entity\EspecieRelevancia as EspecieRelevanciaEntity;

/**
 * Class EspecieRelevanciaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieRelevanciaTest extends DtoTestCase
{
    protected string $dtoClass = EspecieRelevanciaDto::class;

    protected string $entityClass = EspecieRelevanciaEntity::class;
}
