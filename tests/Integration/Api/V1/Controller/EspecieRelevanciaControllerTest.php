<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieRelevanciaControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieRelevanciaController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieRelevanciaResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieRelevanciaControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieRelevanciaControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieRelevanciaController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieRelevanciaResource::class;
}
