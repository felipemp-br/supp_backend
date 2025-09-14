<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ChatParticipanteControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ChatParticipanteController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ChatParticipanteControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatParticipanteControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ChatParticipanteController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ChatParticipanteResource::class;
}
