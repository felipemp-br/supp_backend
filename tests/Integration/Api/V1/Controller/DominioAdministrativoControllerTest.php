<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/DominioAdministrativoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\DominioAdministrativoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DominioAdministrativoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class DominioAdministrativoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DominioAdministrativoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = DominioAdministrativoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = DominioAdministrativoResource::class;
}
