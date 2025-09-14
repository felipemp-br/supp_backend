<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeCopiaTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCopia as ModalidadeCopiaDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeCopia as ModalidadeCopiaEntity;

/**
 * Class ModalidadeCopiaTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeCopiaTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeCopiaDto::class;

    protected string $entityClass = ModalidadeCopiaEntity::class;
}
