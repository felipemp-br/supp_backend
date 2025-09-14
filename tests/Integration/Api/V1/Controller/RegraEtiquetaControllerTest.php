<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/RegraEtiquetaControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\RegraEtiquetaController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RegraEtiquetaResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class RegraEtiquetaControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RegraEtiquetaControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = RegraEtiquetaController::class;

    /**
     * @var string
     */
    protected string $resourceClass = RegraEtiquetaResource::class;
}
