<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/CronjobControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\CronjobController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CronjobResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class CronjobControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = CronjobController::class;

    /**
     * @var string
     */
    protected string $resourceClass = CronjobResource::class;
}
