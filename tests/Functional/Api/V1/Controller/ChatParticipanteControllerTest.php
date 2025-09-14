<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ChatParticipanteControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatParticipanteControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/chat_participante';

    protected int $idToGet = 2;

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'chat' => 1,
        'usuario' => 1,
        'administrador' => true,
    ];

    protected array $jsonPutBody = [
        'chat' => 1,
        'usuario' => 9,
        'mensagensNaoLidas' => 3,
    ];

    protected array $jsonPatchBody = [
        'mensagensNaoLidas' => 0,
    ];

    private int $idChat = 1;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatDeleteMessagesRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idChat, '$idChat deve conter o ID');

        $url = $this->baseUrl.'/limpar_mensagens/'.$this->idChat;
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatDeleteMessagesRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/limpar_mensagens/'.$this->idChat,
            'PATCH',
            null,
            null,
            []
        );

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(401, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatDeleteMessagesRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/limpar_mensagens/0';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
