<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoEspecieProcessoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoEspecieProcessoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEspecieProcessoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoEspecieProcessoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoEspecieProcessoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoEspecieProcessoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoEspecieProcessoWorkflowResource::class;
}
