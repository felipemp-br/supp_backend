<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/EspecieAtividadeControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\EspecieAtividadeController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieAtividadeResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class EspecieAtividadeControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieAtividadeControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = EspecieAtividadeController::class;

    /**
     * @var string
     */
    protected string $resourceClass = EspecieAtividadeResource::class;
}
