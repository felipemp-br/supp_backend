<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/LocalizadorControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\LocalizadorController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LocalizadorResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class LocalizadorControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LocalizadorControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = LocalizadorController::class;

    /**
     * @var string
     */
    protected string $resourceClass = LocalizadorResource::class;
}
