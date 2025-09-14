<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/FormularioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\FormularioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class FormularioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FormularioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = FormularioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = FormularioResource::class;
}
