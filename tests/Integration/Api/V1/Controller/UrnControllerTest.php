<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/UrnControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\UrnController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UrnResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class UrnControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UrnControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = UrnController::class;

    /**
     * @var string
     */
    protected string $resourceClass = UrnResource::class;
}
