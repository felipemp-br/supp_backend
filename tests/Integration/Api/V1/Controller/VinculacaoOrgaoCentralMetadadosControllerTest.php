<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoOrgaoCentralMetadadosControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoOrgaoCentralMetadadosController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoOrgaoCentralMetadadosResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoOrgaoCentralMetadadosControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoOrgaoCentralMetadadosControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoOrgaoCentralMetadadosController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoOrgaoCentralMetadadosResource::class;
}
