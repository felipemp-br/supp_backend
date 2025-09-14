<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class RepositorioControllerTest.
 *
 * @author Lucas Campelo <lucas.campelo@agu.gov.br>
 */
class RepositorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/repositorio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'repositorio-de-teste-post.html',
        'descricao' => 'Descrição do Repositório de Teste POST',
        'documento' => 1,
        'modalidadeRepositorio' => 1,
        'setor' => 2,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'nome' => 'repositorio-de-teste-put.html',
        'descricao' => 'Descrição do Repositório de Teste POST',
        'documento' => 1,
        'modalidadeRepositorio' => 1,
        'setor' => 2,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'repositorio-de-teste-patch.html',
        'setor' => 2,
    ];

    protected int $idToDelete = 1;

    private array $searchCriteria = [
        'where' => '{"id":"eq:1"}',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatSearchRepositoryRouteReturn200(string $username, string $password): void
    {
        static::assertIsArray($this->searchCriteria, '$searchCriteria deve conter um array');

        $url = $this->baseUrl.'/search';
        $response = $this->basicRequest($url, 'GET', $username, $password, $this->searchCriteria);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('total', $responseJson, 'No JSON de resposta, deve conter a chave "total".');
    }

    /**
     * @throws Throwable
     */
    public function testThatSearchRepositoryRouteReturn401(): void
    {
        static::assertIsArray($this->searchCriteria, '$searchCriteria deve conter um array');

        $response = $this->basicRequest(
            $this->baseUrl.'/search',
            'GET',
            null,
            null,
            $this->searchCriteria,
        );

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(401, $response->getStatusCode(), $responseJson['message']);
    }
}
