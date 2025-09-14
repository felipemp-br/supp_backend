<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/CompartilhamentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento as CompartilhamentoDto;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;

/**
 * Class CompartilhamentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompartilhamentoTest extends DtoTestCase
{
    protected string $dtoClass = CompartilhamentoDto::class;

    protected string $entityClass = CompartilhamentoEntity::class;
}
