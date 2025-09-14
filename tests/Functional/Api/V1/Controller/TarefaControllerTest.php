<?php

declare(strict_types=1);
/**
 * /tests/Functional/Controller/TarefaControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class TarefaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TarefaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000012';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tarefa';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'processo' => '1',
        'urgente' => false,
        'especieTarefa' => '2',
        'dataHoraInicioPrazo' => '2021-06-15 15:30:00',
        'dataHoraFinalPrazo' => '2022-12-31 18:00:00',
        'setUsuarioResponsavel' => '1',
        'setorResponsavel' => '6',
    ];

    protected int $idToDelete = 3;

    protected int $idToPut = 3;

    protected array $jsonPutBody = [
        'processo' => '1',
        'setorResponsavel' => '7',
        'especieTarefa' => '2',
        'dataHoraInicioPrazo' => '2022-06-15 15:30:00',
        'dataHoraFinalPrazo' => '2022-12-31 18:00:00',
    ];

    protected int $idToPatch = 3;

    protected array $jsonPatchBody = [
        'dataHoraFinalPrazo' => '2022-12-31 18:00:00',
    ];

    protected int $idUserToAllocate = 10;

    protected int $idToundelete = 4;

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatAllocateTasksRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idUserToAllocate, '$idToAllocate deve conter o ID');

        $url = $this->baseUrl.'/distribuir_tarefas_usuario/'.$this->idUserToAllocate;
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatAllocateTasksRouteReturn400(string $username, string $password): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/distribuir_tarefas_usuario/0',
            'PATCH',
            $username,
            $password,
            []
        );

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatAllocateTasksRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/distribuir_tarefas_usuario/0',
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
    public function testThatAllocateTasksRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/distribuir_tarefas_usuario/X',
            'PATCH',
            $username,
            $password,
            []
        );

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
    public function testThatToogleReadRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToPatch, 'idToPatch deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToPatch.'/toggle_lida';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatToogleReadRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_lida', 'PATCH', null, null, []);

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
    public function testThatToogleReadRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_lida', 'PATCH', $username, $password, []);

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
    public function testThatUndeleteRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToPatch, 'idToPatch deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToundelete.'/undelete';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatUndeleteRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/undelete', 'PATCH', null, null, []);

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
    public function testThatUndeleteRouteReturn400(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/undelete', 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatUndeleteRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/x/undelete', 'PATCH', $username, $password, []);

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
    public function testThatAwareRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToPatch, 'idToPatch deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToPatch.'/ciencia';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatAwareRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/ciencia', 'PATCH', null, null, []);

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
    public function testThatAwareRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/ciencia', 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
