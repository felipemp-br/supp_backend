<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ClassificacaoControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ClassificacaoController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ClassificacaoResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ClassificacaoControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClassificacaoControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ClassificacaoController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ClassificacaoResource::class;
}
