<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/LotacaoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\LotacaoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LotacaoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class LotacaoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LotacaoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = LotacaoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = LotacaoResource::class;
}
