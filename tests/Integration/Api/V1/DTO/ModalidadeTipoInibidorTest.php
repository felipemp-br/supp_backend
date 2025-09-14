<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeTipoInibidorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeTipoInibidor as ModalidadeTipoInibidorDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTipoInibidor as ModalidadeTipoInibidorEntity;

/**
 * Class ModalidadeTipoInibidorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeTipoInibidorTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeTipoInibidorDto::class;

    protected string $entityClass = ModalidadeTipoInibidorEntity::class;
}
