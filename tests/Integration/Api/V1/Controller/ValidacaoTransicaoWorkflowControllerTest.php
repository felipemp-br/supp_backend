<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ValidacaoTransicaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ValidacaoTransicaoWorkflowController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ValidacaoTransicaoWorkflowResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ValidacaoTransicaoWorkflowControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ValidacaoTransicaoWorkflowControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ValidacaoTransicaoWorkflowController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ValidacaoTransicaoWorkflowResource::class;
}
