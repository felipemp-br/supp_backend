<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/DistribuicaoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\DistribuicaoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DistribuicaoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class DistribuicaoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuicaoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = DistribuicaoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = DistribuicaoResource::class;
}
