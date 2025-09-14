<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class NotificacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NotificacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;


    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/notificacao';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'conteudo' => 'Notificação 3',
        'contexto' => null,
        'urgente' => null,
        'destinatario' => 4,
        'remetente' => null,
        'modalidadeNotificacao' => 1,
    ];

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'conteudo' => 'Notificação 4',
        'contexto' => null,
        'urgente' => null,
        'destinatario' => 3,
        'remetente' => null,
        'modalidadeNotificacao' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'conteudo' => 'Notificação 5',
        'contexto' => null,
        'urgente' => null,
        'destinatario' => 4,
        'remetente' => null,
        'modalidadeNotificacao' => 1,
    ];

    protected int $idToDelete = 1;

    private int $idToToggleLidaPatch = 1;

    private int $idToMarcarTodasPatch = 2;

    private int $idToExcluirTodasPatch = 3;

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatToggleLidaPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToToggleLidaPatch, 'idToToggleLidaPatch deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToToggleLidaPatch.'/toggle_lida';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);
        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey(
            'conteudo',
            $responseJson,
            'No JSON de resposta, deve conter a chave "conteudo".'
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatToggleLidaPatchRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToToggleLidaPatch.'/toggle_lida',
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
    public function testThatToggleLidaPatchRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/toggle_lida';
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
    public function testThatMarcarTodasPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToMarcarTodasPatch, 'idToMarcarTodasPatch deve conter o ID');

        $url = $this->baseUrl.'/marcar_todas';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);
        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey(
            'conteudo',
            $responseJson['entities'][0],
            'No JSON de resposta, deve conter a chave "conteudo".'
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatMarcarTodasPatchRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/marcar_todas',
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
    public function testThatMarcarTodasPatchRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/marcar_todas';
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
    public function testThatExcluirTodasPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToExcluirTodasPatch, 'idToExcluirTodasPatch deve conter o ID');

        $url = $this->baseUrl.'/excluir_todas';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);
        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatExcluirTodasPatchRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/excluir_todas',
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
    public function testThatExcluirTodasPatchRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/excluir_todas';
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
    public function testThatPutRouteReturn200(string $username, string $password): void
    {
        $dataExpiracao = new DateTime();
        $dataExpiracao->modify('180 days');

        $this->jsonPutBody['dataHoraExpiracao'] = $dataExpiracao->format('Y-m-d H:i:s');

        parent::testThatPutRouteReturn200($username, $password);
    }
}
