<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TemplateTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Template as TemplateDto;
use SuppCore\AdministrativoBackend\Entity\Template as TemplateEntity;

/**
 * Class TemplateTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TemplateTest extends DtoTestCase
{
    protected string $dtoClass = TemplateDto::class;

    protected string $entityClass = TemplateEntity::class;
}
