<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ColaboradorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ColaboradorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ColaboradorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ColaboradorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ColaboradorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ColaboradorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ColaboradorResource::class;
}
