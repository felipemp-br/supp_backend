<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieSetorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieSetorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieSetorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieSetorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieSetorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieSetorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieSetorResource::class;
}
