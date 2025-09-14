<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeTransicaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeTransicao as ModalidadeTransicaoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTransicao as ModalidadeTransicaoEntity;

/**
 * Class ModalidadeTransicaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeTransicaoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeTransicaoDto::class;

    protected string $entityClass = ModalidadeTransicaoEntity::class;
}
