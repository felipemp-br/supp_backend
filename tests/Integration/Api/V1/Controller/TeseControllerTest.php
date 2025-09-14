<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TeseControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TeseController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TeseResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TeseControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TeseControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TeseController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TeseResource::class;
}
