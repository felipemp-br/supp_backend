<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TipoAcaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TipoAcaoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoAcaoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TipoAcaoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoAcaoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TipoAcaoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TipoAcaoWorkflowResource::class;
}
