<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieDocumentoAvulsoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieDocumentoAvulsoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieDocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieDocumentoAvulsoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumentoAvulsoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieDocumentoAvulsoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieDocumentoAvulsoResource::class;
}
