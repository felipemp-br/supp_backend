<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/DocumentoAvulsoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\DocumentoAvulsoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class DocumentoAvulsoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoAvulsoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = DocumentoAvulsoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = DocumentoAvulsoResource::class;
}
