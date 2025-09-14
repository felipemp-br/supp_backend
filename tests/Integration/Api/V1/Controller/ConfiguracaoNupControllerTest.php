<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ConfiguracaoNupControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ConfiguracaoNupController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfiguracaoNupResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ConfiguracaoNupControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfiguracaoNupControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ConfiguracaoNupController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ConfiguracaoNupResource::class;
}
