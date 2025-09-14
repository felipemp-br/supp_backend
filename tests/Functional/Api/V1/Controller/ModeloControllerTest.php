<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ModeloControllerTest.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class ModeloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modelo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'usuario' => 4,
        'nome' => 'TESTE_USUARIO_4',
        'descricao' => 'DESCRICAO_TESTE_4',
        'template' => 1,
        'modalidadeModelo' => 1,
    ];

    protected int $idToPut = 4;

    protected array $jsonPutBody = [
        'nome' => 'TESTE_USUARIO_UPDATED',
        'descricao' => 'DESCRICAO_TESTE_UPDATED',
        'template' => 1,
        'modalidadeModelo' => 1,
    ];

    protected int $idToPatch = 6;

    protected array $jsonPatchBody = [
        'descricao' => 'DESCRICAO_TESTE_PATCHED',
    ];

    protected int $idToDelete = 5;

    private array $searchCriteria = [
        'where' => '{"id":"eq:1"}',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testModelo',
        ]);
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatSearchModelRouteReturn200(string $username, string $password): void
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
    public function testThatSearchModelRouteReturn401(): void
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
