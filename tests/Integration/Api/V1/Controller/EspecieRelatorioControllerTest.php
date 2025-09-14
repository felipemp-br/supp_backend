<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieRelatorioControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieRelatorioController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieRelatorioResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieRelatorioControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieRelatorioControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieRelatorioController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieRelatorioResource::class;
}
