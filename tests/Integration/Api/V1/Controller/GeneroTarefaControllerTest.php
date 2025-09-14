<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/GeneroTarefaControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\GeneroTarefaController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\GeneroTarefaResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class GeneroTarefaControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroTarefaControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = GeneroTarefaController::class;

    /**
     * @var string
     */
    protected string $resourceClass = GeneroTarefaResource::class;
}
