<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/ValidacaoTransicaoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ValidacaoTransicaoWorkflow as ValidacaoTransicaoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\ValidacaoTransicaoWorkflow as ValidacaoTransicaoWorkflowEntity;

/**
 * Class ValidacaoTransicaoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ValidacaoTransicaoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = ValidacaoTransicaoWorkflowDto::class;

    protected string $entityClass = ValidacaoTransicaoWorkflowEntity::class;
}
