<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/AcaoTransicaoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\AcaoTransicaoWorkflow as AcaoTransicaoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\AcaoTransicaoWorkflow as AcaoTransicaoWorkflowEntity;

/**
 * Class AcaoTransicaoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcaoTransicaoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = AcaoTransicaoWorkflowDto::class;

    protected string $entityClass = AcaoTransicaoWorkflowEntity::class;
}
