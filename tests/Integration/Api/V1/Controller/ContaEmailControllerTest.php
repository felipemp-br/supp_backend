<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ContaEmailControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ContaEmailController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ContaEmailResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ContaEmailControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ContaEmailControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ContaEmailController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ContaEmailResource::class;
}
