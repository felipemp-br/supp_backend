<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/LembreteControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\LembreteController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\LembreteResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class LembreteControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LembreteControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = LembreteController::class;

    /**
     * @var string
     */
    protected string $resourceClass = LembreteResource::class;
}
