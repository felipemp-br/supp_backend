<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroDocumentoAvulsoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroDocumentoAvulsoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroDocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroDocumentoAvulsoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoAvulsoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroDocumentoAvulsoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroDocumentoAvulsoResource::class;
}
