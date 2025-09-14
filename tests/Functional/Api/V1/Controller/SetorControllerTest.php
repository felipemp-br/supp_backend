<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class SetorControllerTest.
 *
 * @author Willian Carvalho <willian.santos@agu.gov.br>
 */
class SetorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/setor';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'especieSetor' => 3,
        'generoSetor' => 1,
        'modalidadeOrgaoCentral' => 1,
        'endereco' => 'rua 25',
        'email' => 'email@email.test.com',
        'sigla' => 'SET',
        'unidade' => 1,
        'parent' => 1,
        'unidadePai' => 1,
        'prefixoNUP' => '00400',
        'gerenciamento' => 1,
        'apenasProtocolo' => 1,
        'numeracaoDocumentoUnidade' => 1,
        'apenasDistribuidor' => 1,
        'distribuicaoCentena' => 1,
        'prazoEqualizacao' => 1,
        'apenasDistribuicaoAutomatica' => 1,
        'comPrevencaoRelativa' => 1,
        'nome' => 'Setor',
        'municipio' => 4813,
        'divergenciaMaxima' => 25,
    ];

    protected int $idToPut = 10;

    protected array $jsonPutBody = [
        'especieSetor' => 3,
        'generoSetor' => 1,
        'modalidadeOrgaoCentral' => 1,
        'endereco' => 'rua 25',
        'email' => 'email@email.test.com',
        'sigla' => 'SET',
        'unidade' => 1,
        'parent' => 1,
        'unidadePai' => 1,
        'prazoEqualizacao' => 1,
        'municipio' => 4813,
        'nome' => 'Setor 10',
        'divergenciaMaxima' => 25,
        'sequenciaInicialNUP' => '10',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'Setor2',
    ];

    protected int $idToDelete = 21;

    private int $idToTransferProcess = 5;

    private int $idDestino = 17;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatTransferProcessRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToTransferProcess, '$idToTransferProcess deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToTransferProcess.'/transferir_processos_unidade/'.$this->idDestino;
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatTransferProcessRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToTransferProcess.'/transferir_processos_unidade/'.$this->idDestino,
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
    public function testThatTransferProcessRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/transferir_processos_unidade/'.$this->idDestino;
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
