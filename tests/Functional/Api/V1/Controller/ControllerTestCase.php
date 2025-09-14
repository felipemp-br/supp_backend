<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ControllerTestCase.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class ControllerTestCase extends WebTestCase
{
    protected static string $username;

    protected static string $password;

    protected string $baseUrl;

    protected int $idToGet;

    protected int $idToPut;

    protected array $jsonPostBody;

    protected array $jsonPutBody;

    protected int $idToPatch;

    protected array $jsonPatchBody;

    protected int $idToDelete;

    /**
     * @throws Throwable
     */
    public function testThatGetBaseRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl, 'GET', null, null, []);

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
    public function testThatGetBaseRouteReturn200(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatPostRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl, 'POST', null, null, []);

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
    public function testThatPostRouteReturn201(string $username, string $password): void
    {
        static::assertNotEmpty($this->jsonPostBody, '$jsonPostBody deve conter um array de dados');

        $response = $this->basicRequest($this->baseUrl, 'POST', $username, $password, $this->jsonPostBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(201, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatPutRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'PUT', null, null, []);

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
    public function testThatPutRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'PUT', $username, $password, []);

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
        static::assertNotEmpty($this->jsonPutBody, '$jsonPutBody deve conter dados.');
        static::assertNotEmpty($this->idToPut, '$idToPut deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToPut;
        $response = $this->basicRequest($url, 'PUT', $username, $password, $this->jsonPutBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatGetOneRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'GET', null, null, []);

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
    public function testThatGetOneRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'GET', $username, $password, []);

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
    public function testThatGetOneRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToGet, '$idToGet deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToGet;
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatPatchRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'PATCH', null, null, []);

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
    public function testThatPatchRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'PATCH', $username, $password, []);

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
    public function testThatPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->jsonPatchBody, '$jsonPatchBody deve conter dados.');
        static::assertNotEmpty($this->idToPatch, '$idToPatch deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToPatch;
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonPatchBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatDeleteRouteReturn401()
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'DELETE', null, null, []);

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
    public function testThatDeleteRouteReturn404(string $username, string $password)
    {
        $response = $this->basicRequest($this->baseUrl.'/0', 'DELETE', $username, $password, []);

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
    public function testThatDeleteRouteReturn200(string $username, string $password)
    {
        static::assertNotEmpty($this->idToDelete, '$idToDelete deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToDelete;
        $response = $this->basicRequest($url, 'DELETE', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public static function usernamePasswordProvider(): array
    {
        if (!static::$username) {
            static::fail('No data in $username');
        }

        if (!static::$password) {
            static::fail('No data in $password');
        }

        return [
            [static::$username, static::$password],
        ];
    }
}
