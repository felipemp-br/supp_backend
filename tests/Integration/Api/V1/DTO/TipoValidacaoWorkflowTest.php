<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/DTO/TipoValidacaoWorkflowTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\DTO;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoValidacaoWorkflow as TipoValidacaoWorkflowDto;
use SuppCore\AdministrativoBackend\Entity\TipoValidacaoWorkflow as TipoValidacaoWorkflowEntity;

/**
 * Class TipoValidacaoWorkflowTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoValidacaoWorkflowTest extends DtoTestCase
{
    protected string $dtoClass = TipoValidacaoWorkflowDto::class;

    protected string $entityClass = TipoValidacaoWorkflowEntity::class;
}
