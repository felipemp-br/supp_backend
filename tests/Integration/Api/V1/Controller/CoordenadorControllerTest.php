<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/CoordenadorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\CoordenadorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CoordenadorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class CoordenadorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CoordenadorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = CoordenadorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = CoordenadorResource::class;
}
