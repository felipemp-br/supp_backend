<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroSetorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroSetorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroSetorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroSetorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroSetorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroSetorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroSetorResource::class;
}
