<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeColaboradorTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeColaborador as ModalidadeColaboradorDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeColaborador as ModalidadeColaboradorEntity;

/**
 * Class ModalidadeColaboradorTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeColaboradorTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeColaboradorDto::class;

    protected string $entityClass = ModalidadeColaboradorEntity::class;
}
