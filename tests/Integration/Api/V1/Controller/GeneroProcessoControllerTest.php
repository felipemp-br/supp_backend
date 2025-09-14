<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroProcessoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroProcessoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroProcessoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroProcessoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroProcessoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroProcessoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroProcessoResource::class;
}
