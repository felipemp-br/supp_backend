<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/GeneroRelevanciaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelevancia as GeneroRelevanciaDto;
use SuppCore\AdministrativoBackend\Entity\GeneroRelevancia as GeneroRelevanciaEntity;

/**
 * Class GeneroRelevanciaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroRelevanciaTest extends DtoTestCase
{
    protected string $dtoClass = GeneroRelevanciaDto::class;

    protected string $entityClass = GeneroRelevanciaEntity::class;
}
