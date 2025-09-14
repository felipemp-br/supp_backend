<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/RelatorioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\RelatorioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelatorioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class RelatorioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelatorioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = RelatorioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = RelatorioResource::class;
}
