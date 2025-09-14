<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/SetorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\SetorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class SetorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SetorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = SetorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = SetorResource::class;
}
