<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ServidorEmailControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ServidorEmailController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ServidorEmailResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ServidorEmailControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ServidorEmailControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ServidorEmailController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ServidorEmailResource::class;
}
