<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/DesentranhamentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;

/**
 * Class DesentranhamentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DesentranhamentoTest extends DtoTestCase
{
    protected string $dtoClass = DesentranhamentoDto::class;

    protected string $entityClass = DesentranhamentoEntity::class;
}
