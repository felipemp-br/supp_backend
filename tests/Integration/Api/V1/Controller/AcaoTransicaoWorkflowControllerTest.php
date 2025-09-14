<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/AcaoTransicaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\AcaoTransicaoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AcaoTransicaoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class AcaoTransicaoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcaoTransicaoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = AcaoTransicaoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = AcaoTransicaoWorkflowResource::class;
}
