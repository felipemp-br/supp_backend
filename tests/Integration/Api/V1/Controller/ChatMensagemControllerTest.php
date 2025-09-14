<?php

declare(strict_types=1);
/**
 * /tests/Integration/Api/V1/Controller/ChatMensagemControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Api\V1\Controller\ChatMensagemController;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatMensagemResource;
use SuppCore\AdministrativoBackend\Utils\Tests\RestIntegrationControllerTestCase;

/**
 * Class ChatMensagemControllerTest.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Integration\Api\V1\Controller;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatMensagemControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var string
     */
    protected string $controllerClass = ChatMensagemController::class;

    /**
     * @var string
     */
    protected string $resourceClass = ChatMensagemResource::class;
}
