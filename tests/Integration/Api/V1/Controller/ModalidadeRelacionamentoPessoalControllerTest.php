<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ModalidadeRelacionamentoPessoalControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ModalidadeRelacionamentoPessoalController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeRelacionamentoPessoalResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ModalidadeRelacionamentoPessoalControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRelacionamentoPessoalControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ModalidadeRelacionamentoPessoalController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ModalidadeRelacionamentoPessoalResource::class;
}
