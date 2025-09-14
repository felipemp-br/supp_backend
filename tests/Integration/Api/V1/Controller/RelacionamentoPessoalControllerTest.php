<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/RelacionamentoPessoalControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\RelacionamentoPessoalController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelacionamentoPessoalResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class RelacionamentoPessoalControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelacionamentoPessoalControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = RelacionamentoPessoalController::class;

    /**
     * @var string
     */
    protected string $resourceClass = RelacionamentoPessoalResource::class;
}
