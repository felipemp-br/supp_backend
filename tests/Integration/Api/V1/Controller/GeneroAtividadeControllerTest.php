<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroAtividadeControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroAtividadeController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroAtividadeResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroAtividadeControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroAtividadeControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroAtividadeController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroAtividadeResource::class;
}
