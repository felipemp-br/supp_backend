<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/TipoDossieControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\TipoDossieController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoDossieResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class TipoDossieControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDossieControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = TipoDossieController::class;

    /**
     * @var string
     */
    protected string $resourceClass = TipoDossieResource::class;
}
