<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TipoRelatorioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TipoRelatorioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoRelatorioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TipoRelatorioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoRelatorioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TipoRelatorioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TipoRelatorioResource::class;
}
