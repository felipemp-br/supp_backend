<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TipoValidacaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TipoValidacaoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoValidacaoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TipoValidacaoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoValidacaoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TipoValidacaoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TipoValidacaoWorkflowResource::class;
}
