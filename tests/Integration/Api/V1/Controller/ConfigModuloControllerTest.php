<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ConfigModuloControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ConfigModuloController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ConfigModuloResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ConfigModuloControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfigModuloControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ConfigModuloController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ConfigModuloResource::class;
}
