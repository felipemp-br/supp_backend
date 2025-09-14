<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ChatMensagemControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatMensagemControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/chat_mensagem';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'mensagem' => 'REPLY TEST',
        'chat' => 1,
        'replyTo' => 2,
        'componenteDigital' => 1,
    ];

    protected array $jsonPutBody = [
        'mensagem' => 'REPLY TEST',
        'chat' => 1,
        'replyTo' => 1,
        'usuario' => 6,
        'componenteDigital' => 2,
    ];

    protected array $jsonPatchBody = [
        'componenteDigital' => 3,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
