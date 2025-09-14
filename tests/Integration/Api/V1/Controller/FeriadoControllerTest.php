<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/FeriadoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\FeriadoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FeriadoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class FeriadoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FeriadoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = FeriadoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = FeriadoResource::class;
}
