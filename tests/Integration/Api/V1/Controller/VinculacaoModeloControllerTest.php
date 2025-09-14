<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoModeloControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoModeloController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoModeloResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoModeloControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoModeloControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoModeloController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoModeloResource::class;
}
