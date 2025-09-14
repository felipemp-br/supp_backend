<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroDocumentoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroDocumentoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroDocumentoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroDocumentoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroDocumentoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroDocumentoResource::class;
}
