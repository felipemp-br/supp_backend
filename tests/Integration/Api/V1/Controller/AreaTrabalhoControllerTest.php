<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/AreaTrabalhoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\AreaTrabalhoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AreaTrabalhoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class AreaTrabalhoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AreaTrabalhoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = AreaTrabalhoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = AreaTrabalhoResource::class;
}
