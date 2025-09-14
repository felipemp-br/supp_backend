<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeMeioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeMeio as ModalidadeMeioDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio as ModalidadeMeioEntity;

/**
 * Class ModalidadeMeioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeMeioTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeMeioDto::class;

    protected string $entityClass = ModalidadeMeioEntity::class;
}
