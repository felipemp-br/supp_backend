<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class PessoaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PessoaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/pessoa';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'naturalidade' => 4813,
        'nacionalidade' => 31,
        'modalidadeGeneroPessoa' => 1,
        'modalidadeQualificacaoPessoa' => 1,
        'origemDados' => 1,
        'profissao' => 'AUTÔNOMO',
        'contato' => 'CONTATO',
        'pessoaValidada' => false,
        'pessoaConveniada' => false,
        'dataNascimento' => '1970-01-01',
        'dataHoraIndexacao' => null,
        'nome' => 'PESSOA',
        'numeroDocumentoPrincipal' => '12345678909',
        'nomeGenitor' => 'GENITOR',
        'nomeGenitora' => 'GENITORA',
    ];

    protected array $jsonPutBody = [
        'naturalidade' => 4813,
        'nacionalidade' => 31,
        'modalidadeGeneroPessoa' => 1,
        'modalidadeQualificacaoPessoa' => 1,
        'nome' => 'PESSOA',
        'numeroDocumentoPrincipal' => '12345678909',
        'pessoaValidada' => true,
        'pessoaConveniada' => true,
    ];

    protected array $jsonPatchBody = [
        'numeroDocumentoPrincipal' => '12345678909',
        'pessoaValidada' => false,
        'pessoaConveniada' => false,
    ];

    private array $searchCriteria = [
        'where' => '{"pessoaValidada":"eq:true"}',
        'limit' => 10,
        'offset' => 1,
        'order' => '{"id":"desc"}',
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
    public function testThatSearchPersonRouteReturn200(string $username, string $password): void
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
    #[DataProvider('usernamePasswordProvider')]
    public function testThatSearchPersonRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/search';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }
}
