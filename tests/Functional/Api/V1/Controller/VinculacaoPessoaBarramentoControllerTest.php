<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class VinculacaoPessoaBarramentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoPessoaBarramentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_pessoa_barramento';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'pessoa' => 3,
        'nomeRepositorio' => 'Nome do Repositório',
        'repositorio' => 1,
        'nomeEstrutura' => 'Nome do Estrutura',
        'estrutura' => 1,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'pessoa' => 3,
        'nomeRepositorio' => 'Nome do Repositório',
        'repositorio' => 1,
        'nomeEstrutura' => 'Nome do Estrutura',
        'estrutura' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'pessoa' => 3,
        'nomeRepositorio' => 'Nome do Repositório',
        'repositorio' => 1,
        'nomeEstrutura' => 'Nome do Estrutura',
        'estrutura' => 1,
    ];

    protected int $idToDelete = 1;

    protected int $idToOthersTests = 1;

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatConsultaRepositorioGetRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToOthersTests, 'idToToggleLidaPatch deve conter o ID');

        $url = $this->baseUrl.'/consulta_repositorio';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatConsultaRepositorioGetRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/consulta_repositorio',
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
    public function testThatConsultaRepositorioGetRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/consulta_repositorio';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

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
    public function testThatConsultaEstruturaGetRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToOthersTests, 'idToToggleLidaPatch deve conter o ID');

        $url = $this->baseUrl.'/consulta_estrutura';
        $response = $this->basicRequest($url, 'GET', $username, $password, ['repositorio' => 26]);
        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatConsultaEstruturaGetRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/consulta_estrutura',
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
    public function testThatConsultaEstruturaGetRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/consulta_estrutura';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
