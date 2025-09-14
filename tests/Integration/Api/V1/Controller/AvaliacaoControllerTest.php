<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/AvaliacaoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\AvaliacaoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AvaliacaoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class AvaliacaoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AvaliacaoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = AvaliacaoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = AvaliacaoResource::class;
}
