<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class TipoRelatorioControllerTest.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoRelatorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_relatorio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'GERENCIAL Teste',
        'templateHTML' => 'TemplateHTML1',
        'DQL' => 'SQLTESTE1',
        'parametros' => 'parametros',
        'descricao' => 'Descrição 1',
        'especieRelatorio' => 1,
        'ativo' => true,
        'somenteExcel' => true,
        'limite' => 1,
    ];

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected array $jsonPutBody = [
        'nome' => 'TESTES Atual',
        'templateHTML' => 'TemplateHTML1',
        'DQL' => 'SQLTESTE1',
        'parametros' => 'parametros',
        'descricao' => 'Descrição 1',
        'somenteExcel' => false,
        'limite' => 1,
        'especieRelatorio' => 5,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'descricao' => 'Descrição Atualizado',
    ];

    private int $idVisibility = 382;

    private array $jsonCreateVisibility = [
        'ROLE_TEST',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatCreateVisibilityRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToGet, '$idToGet deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToGet.'/visibilidade';
        $response = $this->basicRequest($url, 'PUT', $username, $password, $this->jsonCreateVisibility);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatCreateVisibilityRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/visibilidade';
        $response = $this->basicRequest($url, 'PUT', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatCreateVisibilityRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToGet.'/visibilidade',
            'PUT',
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
    public function testThatGetVisibilityRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToGet, '$idToGet deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToGet.'/visibilidade';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('poderes', $responseJson[0], 'No JSON de resposta, deve conter a chave "poderes".');
        static::assertArrayHasKey('valor', $responseJson[0], 'No JSON de resposta, deve conter a chave "valor".');
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatGetVisibilityRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/visibilidade';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatGetVisibilityRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToGet.'/visibilidade',
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

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatDestroyVisibilityRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToGet, '$idToGet deve conter o ID');
        static::assertNotEmpty($this->idVisibility, '$idVisibility deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToGet.'/visibilidade/'.$this->idVisibility;
        $response = $this->basicRequest($url, 'DELETE', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatDestroyVisibilityRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/visibilidade/0';
        $response = $this->basicRequest($url, 'DELETE', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatDestroyVisibilityRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToGet.'/visibilidade/'.$this->idVisibility,
            'DELETE',
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
    public function testThatGetStaticRolesReturn200(string $username, string $password): void
    {
        $url = $this->baseUrl.'/get_roles_tipo_relatorio';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }
}
