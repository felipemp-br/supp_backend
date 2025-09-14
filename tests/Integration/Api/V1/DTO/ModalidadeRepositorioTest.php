<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeRepositorioTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRepositorio as ModalidadeRepositorioDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepositorio as ModalidadeRepositorioEntity;

/**
 * Class ModalidadeRepositorioTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRepositorioTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeRepositorioDto::class;

    protected string $entityClass = ModalidadeRepositorioEntity::class;
}
