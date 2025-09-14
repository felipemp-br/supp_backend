<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoRepositorioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoRepositorioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRepositorioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoRepositorioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoRepositorioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoRepositorioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoRepositorioResource::class;
}
