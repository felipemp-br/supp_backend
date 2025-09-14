<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/RamoDireitoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\RamoDireitoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RamoDireitoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class RamoDireitoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RamoDireitoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = RamoDireitoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = RamoDireitoResource::class;
}
