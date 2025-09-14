<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ObjetoAvaliadoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ObjetoAvaliadoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ObjetoAvaliadoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ObjetoAvaliadoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ObjetoAvaliadoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ObjetoAvaliadoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ObjetoAvaliadoResource::class;
}
