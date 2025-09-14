<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class WorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class WorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected int $idToViewtransicoes = 1;

    protected array $jsonPostBody = [
        'nome' => 'TESTE - POST',
        'descricao' => 'TESTE - POST',
        'especieTarefaInicial' => 1,
        'generoProcesso' => 1,
    ];

    protected array $jsonPutBody = [
        'nome' => 'TESTE - PUT',
        'descricao' => 'TESTE - PUT',
        'especieTarefaInicial' => 1,
        'generoProcesso' => 2,
    ];

    protected array $jsonPatchBody = [
        'nome' => 'TESTE - PATCH',
        'descricao' => 'TESTE - PUT',
        'especieTarefaInicial' => 1,
    ];

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatViewtransicoesGetRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToViewtransicoes, 'idToViewtransicoes deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToViewtransicoes.'/view/transicoes';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);
        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatViewtransicoesGetRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToViewtransicoes.'/view/transicoes',
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
    public function testThatViewtransicoesGetRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/view/transicoes';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
