<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/FolderControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\FolderController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FolderResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class FolderControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FolderControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = FolderController::class;

    /**
     * @var string
     */
    protected string $resourceClass = FolderResource::class;
}
