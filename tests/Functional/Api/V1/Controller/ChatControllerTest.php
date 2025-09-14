<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ChatControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChatControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/chat';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'nome' => 'CHAT ABC',
        'descricao' => 'DESCRIÇÃO CHAT ABC',
        'grupo' => true,
        'capa' => 1,
    ];

    protected array $jsonPutBody = [
        'nome' => 'CHAT 123',
        'descricao' => 'DESCRIÇÃO CHAT 123',
        'grupo' => false,
        'capa' => null,
    ];

    protected array $jsonPatchBody = [
        'nome' => 'CHAT',
        'descricao' => 'DESCRIÇÃO CHAT',
    ];

    private int $idParticipant = 6;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatCreateOrRecoveryChatRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idParticipant, '$idParticipant deve conter o ID');

        $url = $this->baseUrl.'/criar_ou_retornar/'.$this->idParticipant;
        $response = $this->basicRequest($url, 'POST', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatCreateOrRecoveryChatRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/criar_ou_retornar/'.$this->idParticipant,
            'POST',
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
    public function testThatCreateOrRecoveryChatRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/criar_ou_retornar/x';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatFindChatListRouteReturn200(string $username, string $password): void
    {
        $url = $this->baseUrl.'/find_chat_list';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatFindChatListRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/find_chat_list',
            'GET',
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
}
