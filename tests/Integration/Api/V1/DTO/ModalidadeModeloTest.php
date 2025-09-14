<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeModeloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeModelo as ModalidadeModeloDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo as ModalidadeModeloEntity;

/**
 * Class ModalidadeModeloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeModeloTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeModeloDto::class;

    protected string $entityClass = ModalidadeModeloEntity::class;
}
