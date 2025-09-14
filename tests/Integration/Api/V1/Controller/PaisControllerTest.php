<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/PaisControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\PaisController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PaisResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class PaisControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PaisControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = PaisController::class;

    /**
     * @var string
     */
    protected string $resourceClass = PaisResource::class;
}
