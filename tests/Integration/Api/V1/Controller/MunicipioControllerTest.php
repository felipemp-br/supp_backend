<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/MunicipioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\MunicipioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\MunicipioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class MunicipioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MunicipioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = MunicipioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = MunicipioResource::class;
}
