<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ModalidadeUrnControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ModalidadeUrnController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeUrnResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ModalidadeUrnControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeUrnControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ModalidadeUrnController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ModalidadeUrnResource::class;
}
