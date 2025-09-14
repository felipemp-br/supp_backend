<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoAvisoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoAvisoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoAvisoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoAvisoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoAvisoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoAvisoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoAvisoResource::class;
}
