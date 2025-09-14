<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/DocumentoIdentificadorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\DocumentoIdentificadorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoIdentificadorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class DocumentoIdentificadorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoIdentificadorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = DocumentoIdentificadorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = DocumentoIdentificadorResource::class;
}
