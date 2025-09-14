<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeFaseTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFase as ModalidadeFaseDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase as ModalidadeFaseEntity;

/**
 * Class ModalidadeFaseTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeFaseTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeFaseDto::class;

    protected string $entityClass = ModalidadeFaseEntity::class;
}
