<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ParametroAdministrativoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ParametroAdministrativoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ParametroAdministrativoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ParametroAdministrativoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ParametroAdministrativoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ParametroAdministrativoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ParametroAdministrativoResource::class;
}
