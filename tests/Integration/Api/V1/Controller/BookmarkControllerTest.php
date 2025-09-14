<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/BookmarkControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\BookmarkController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\BookmarkResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class BookmarkControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class BookmarkControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = BookmarkController::class;

    /**
     * @var string
     */
    protected string $resourceClass = BookmarkResource::class;
}
