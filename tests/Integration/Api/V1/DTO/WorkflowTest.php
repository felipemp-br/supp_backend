<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/WorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDto;
use SuppCore\AdministrativoBackend\Entity\Workflow as WorkflowEntity;

/**
 * Class WorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class WorkflowTest extends DtoTestCase
{
    protected string $dtoClass = WorkflowDto::class;

    protected string $entityClass = WorkflowEntity::class;
}
