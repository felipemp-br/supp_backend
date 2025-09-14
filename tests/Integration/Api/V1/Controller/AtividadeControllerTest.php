<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/AtividadeControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\AtividadeController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AtividadeResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class AtividadeControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AtividadeControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = AtividadeController::class;

    /**
     * @var string
     */
    protected string $resourceClass = AtividadeResource::class;
}
