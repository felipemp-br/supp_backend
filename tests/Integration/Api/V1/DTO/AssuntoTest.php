<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AssuntoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto as AssuntoDto;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;

/**
 * Class AssuntoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssuntoTest extends DtoTestCase
{
    protected string $dtoClass = AssuntoDto::class;

    protected string $entityClass = AssuntoEntity::class;
}
