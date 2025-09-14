<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/VinculacaoMetadadosControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\VinculacaoMetadadosController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoMetadadosResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class VinculacaoMetadadosControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoMetadadosControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = VinculacaoMetadadosController::class;

    /**
     * @var string
     */
    protected string $resourceClass = VinculacaoMetadadosResource::class;
}
