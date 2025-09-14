<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeInteressadoTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeInteressado as ModalidadeInteressadoDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeInteressado as ModalidadeInteressadoEntity;

/**
 * Class ModalidadeInteressadoTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeInteressadoTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeInteressadoDto::class;

    protected string $entityClass = ModalidadeInteressadoEntity::class;
}
