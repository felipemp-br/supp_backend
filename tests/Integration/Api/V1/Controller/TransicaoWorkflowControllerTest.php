<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TransicaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TransicaoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TransicaoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TransicaoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransicaoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TransicaoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TransicaoWorkflowResource::class;
}
