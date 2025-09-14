<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TemplateControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TemplateController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TemplateResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TemplateControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TemplateControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TemplateController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TemplateResource::class;
}
