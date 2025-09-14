<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieProcessoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieProcessoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieProcessoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieProcessoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieProcessoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieProcessoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieProcessoResource::class;
}
