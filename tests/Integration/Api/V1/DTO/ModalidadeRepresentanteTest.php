<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeRepresentanteTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeRepresentante as ModalidadeRepresentanteDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeRepresentante as ModalidadeRepresentanteEntity;

/**
 * Class ModalidadeRepresentanteTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRepresentanteTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeRepresentanteDto::class;

    protected string $entityClass = ModalidadeRepresentanteEntity::class;
}
