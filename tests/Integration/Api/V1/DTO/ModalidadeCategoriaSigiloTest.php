<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeCategoriaSigiloTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCategoriaSigilo as ModalidadeCategoriaSigiloDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeCategoriaSigilo as ModalidadeCategoriaSigiloEntity;

/**
 * Class ModalidadeCategoriaSigiloTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeCategoriaSigiloTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeCategoriaSigiloDto::class;

    protected string $entityClass = ModalidadeCategoriaSigiloEntity::class;
}
