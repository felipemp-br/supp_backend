<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ModalidadeTemplateControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ModalidadeTemplateController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeTemplateResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ModalidadeTemplateControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeTemplateControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ModalidadeTemplateController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ModalidadeTemplateResource::class;
}
