<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TransicaoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TransicaoWorkflow as TransicaoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\TransicaoWorkflow as TransicaoWorkflowEntity;

/**
 * Class TransicaoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransicaoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = TransicaoWorkflowDto::class;

    protected string $entityClass = TransicaoWorkflowEntity::class;
}
