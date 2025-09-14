<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieDocumentoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieDocumentoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieDocumentoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieDocumentoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumentoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieDocumentoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieDocumentoResource::class;
}
