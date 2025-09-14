<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ModalidadeDocumentoIdentificadorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ModalidadeDocumentoIdentificadorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeDocumentoIdentificadorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ModalidadeDocumentoIdentificadorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeDocumentoIdentificadorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ModalidadeDocumentoIdentificadorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ModalidadeDocumentoIdentificadorResource::class;
}
