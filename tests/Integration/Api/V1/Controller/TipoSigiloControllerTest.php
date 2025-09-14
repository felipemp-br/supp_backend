<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TipoSigiloControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TipoSigiloController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoSigiloResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TipoSigiloControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoSigiloControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TipoSigiloController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TipoSigiloResource::class;
}
