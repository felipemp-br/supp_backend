<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroRelatorioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroRelatorioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroRelatorioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroRelatorioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroRelatorioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroRelatorioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroRelatorioResource::class;
}
