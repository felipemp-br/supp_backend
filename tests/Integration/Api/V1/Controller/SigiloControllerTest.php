<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/SigiloControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\SigiloController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SigiloResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class SigiloControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SigiloControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = SigiloController::class;

    /**
     * @var string
     */
    protected string $resourceClass = SigiloResource::class;
}
