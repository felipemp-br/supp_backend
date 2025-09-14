<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class EnderecoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EnderecoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/endereco';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'municipio' => 5268,
        'pessoa' => 1,
        'origemDados' => 1,
        'bairro' => 'Jardim Paulista',
        'numero' => '885',
        'logradouro' => 'Rua Haddock Lobo',
        'cep' => '01414-001',
        'complemento' => 'complemento',
    ];

    protected array $jsonPutBody = [
        'pessoa' => 2,
        'complemento' => 'complemento',
        'observacao' => 'observacao',
        'cep' => '01411-001',
        'bairro' => 'Lapa',
    ];

    protected array $jsonPatchBody = [
        'complemento' => null,
        'observacao' => null,
    ];

    private string $cep = '13233-280';

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatFindCepRouteReturn200(string $username, string $password): void
    {
        static::markTestSkipped();

        $url = $this->baseUrl.'/'.$this->cep.'/correios';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatFindCepRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->cep.'/correios',
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
    public function testThatFindCepRouteReturn404(string $username, string $password): void
    {
        static::markTestSkipped();

        $url = $this->baseUrl.'/00000000/correios';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
