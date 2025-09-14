<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ModalidadeTemplateTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeTemplate as ModalidadeTemplateDto;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTemplate as ModalidadeTemplateEntity;

/**
 * Class ModalidadeTemplateTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeTemplateTest extends DtoTestCase
{
    protected string $dtoClass = ModalidadeTemplateDto::class;

    protected string $entityClass = ModalidadeTemplateEntity::class;
}
