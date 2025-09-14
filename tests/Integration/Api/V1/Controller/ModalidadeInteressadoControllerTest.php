<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ModalidadeInteressadoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ModalidadeInteressadoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeInteressadoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ModalidadeInteressadoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeInteressadoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ModalidadeInteressadoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ModalidadeInteressadoResource::class;
}
