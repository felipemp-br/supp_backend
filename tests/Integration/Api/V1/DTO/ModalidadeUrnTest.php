<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeUrnTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeUrn as ModalidadeUrnDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeUrn as ModalidadeUrnEntity;

/**
 * Class ModalidadeUrnTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeUrnTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeUrnDto::class;

    protected string $entityClass = ModalidadeUrnEntity::class;
}
