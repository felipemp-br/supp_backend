<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/FavoritoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\FavoritoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FavoritoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class FavoritoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FavoritoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = FavoritoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = FavoritoResource::class;
}
