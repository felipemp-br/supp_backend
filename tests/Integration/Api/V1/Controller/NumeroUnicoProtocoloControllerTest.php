<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/NumeroUnicoProtocoloControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\NumeroUnicoProtocoloController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NumeroUnicoProtocoloResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class NumeroUnicoProtocoloControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroUnicoProtocoloControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = NumeroUnicoProtocoloController::class;

    /**
     * @var string
     */
    protected string $resourceClass = NumeroUnicoProtocoloResource::class;
}
