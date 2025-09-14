<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeDestinacaoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeDestinacao as ModalidadeDestinacaoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDestinacao as ModalidadeDestinacaoEntity;

/**
 * Class ModalidadeDestinacaoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeDestinacaoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeDestinacaoDto::class;

    protected string $entityClass = ModalidadeDestinacaoEntity::class;
}
