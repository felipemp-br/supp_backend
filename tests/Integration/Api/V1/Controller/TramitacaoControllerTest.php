<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TramitacaoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TramitacaoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TramitacaoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TramitacaoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TramitacaoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TramitacaoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TramitacaoResource::class;
}
