<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoTransicaoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoTransicaoWorkflow as VinculacaoTransicaoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoTransicaoWorkflow as VinculacaoTransicaoWorkflowEntity;

/**
 * Class VinculacaoTransicaoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoTransicaoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoTransicaoWorkflowDto::class;

    protected string $entityClass = VinculacaoTransicaoWorkflowEntity::class;
}
