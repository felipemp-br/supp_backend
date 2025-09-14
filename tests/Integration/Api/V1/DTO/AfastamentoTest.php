<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AfastamentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Afastamento as AfastamentoDto;
use SuppCore\AdministrativoBackend\Entity\Afastamento as AfastamentoEntity;

/**
 * Class AfastamentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AfastamentoTest extends DtoTestCase
{
    protected string $dtoClass = AfastamentoDto::class;

    protected string $entityClass = AfastamentoEntity::class;
}
