<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/NomeControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\NomeController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NomeResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class NomeControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NomeControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = NomeController::class;

    /**
     * @var string
     */
    protected string $resourceClass = NomeResource::class;
}
