<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeAlvoInibidorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeAlvoInibidor as ModalidadeAlvoInibidorDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeAlvoInibidor as ModalidadeAlvoInibidorEntity;

/**
 * Class ModalidadeAlvoInibidorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeAlvoInibidorTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeAlvoInibidorDto::class;

    protected string $entityClass = ModalidadeAlvoInibidorEntity::class;
}
