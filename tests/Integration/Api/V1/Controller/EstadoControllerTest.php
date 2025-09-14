<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EstadoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EstadoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EstadoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EstadoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EstadoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EstadoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EstadoResource::class;
}
