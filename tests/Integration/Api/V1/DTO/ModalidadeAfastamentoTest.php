<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeAfastamentoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAfastamento as ModalidadeAfastamentoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAfastamento as ModalidadeAfastamentoEntity;

/**
 * Class ModalidadeAfastamentoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeAfastamentoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeAfastamentoDto::class;

    protected string $entityClass = ModalidadeAfastamentoEntity::class;
}
