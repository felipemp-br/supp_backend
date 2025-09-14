<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeFolderTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeFolder as ModalidadeFolderDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFolder as ModalidadeFolderEntity;

/**
 * Class ModalidadeFolderTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeFolderTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeFolderDto::class;

    protected string $entityClass = ModalidadeFolderEntity::class;
}
