<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class JuntadaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class JuntadaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/juntada';

    protected int $idToGet = 2;

    protected array $jsonPostBody = [
        'ativo' => true,
        'vinculada' => true,
        'descricao' => 'TESTE_14',
        'numeracaoSequencial' => '14',
        'atividade' => '1',
        'documento' => '2',
        'documentoAvulso' => null,
        'documentoJuntadaAtual' => null,
        'origemDados' => '1',
        'tarefa' => '1',
        'volume' => '2',
        'criadoPor' => '4',
    ];

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'ativo' => true,
        'vinculada' => true,
        'descricao' => 'TESTE_15',
        'numeracaoSequencial' => '15',
        'documento' => '1',
        'documentoAvulso' => '1',
        'documentoJuntadaAtual' => '1',
        'origemDados' => '1',
        'tarefa' => '1',
        'volume' => '2',
        'criadoPor' => '4',
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'ativo' => true,
        'vinculada' => true,
        'descricao' => 'TESTE_16',
        'numeracaoSequencial' => '16',
        'documento' => '1',
        'documentoAvulso' => '1',
        'documentoJuntadaAtual' => '1',
        'origemDados' => '1',
        'tarefa' => '1',
        'volume' => '2',
        'criadoPor' => '4',
    ];

    private int $idSendEmail = 2;

    /**
     * @throws Throwable
     */
    public function testThatSendEmailGetRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idSendEmail.'/sendEmail',
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
    public function testThatSendEmailGetRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/sendEmail';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }
}
