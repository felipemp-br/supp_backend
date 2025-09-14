<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/DesentranhamentoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\DesentranhamentoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DesentranhamentoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class DesentranhamentoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DesentranhamentoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = DesentranhamentoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = DesentranhamentoResource::class;
}
