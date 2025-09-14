<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/OrigemDadosTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDto;
use SuppCore\AdministrativoBackend\Entity\OrigemDados as OrigemDadosEntity;

/**
 * Class OrigemDadosTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OrigemDadosTest extends DtoTestCase
{
    protected string $dtoClass = OrigemDadosDto::class;

    protected string $entityClass = OrigemDadosEntity::class;
}
