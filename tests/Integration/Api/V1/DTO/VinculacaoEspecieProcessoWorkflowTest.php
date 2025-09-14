<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/VinculacaoEspecieProcessoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEspecieProcessoWorkflow as VinculacaoEspecieProcessoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEspecieProcessoWorkflow as VinculacaoEspecieProcessoWorkflowEntity;

/**
 * Class VinculacaoEspecieProcessoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoEspecieProcessoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = VinculacaoEspecieProcessoWorkflowDto::class;

    protected string $entityClass = VinculacaoEspecieProcessoWorkflowEntity::class;
}
