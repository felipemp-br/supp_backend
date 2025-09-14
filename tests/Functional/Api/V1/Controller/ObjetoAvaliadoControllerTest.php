<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ObjetoAvaliadoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ObjetoAvaliadoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/objeto_avaliado';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'classe' => 'TESTE POST',
        'objetoId' => 123,
    ];

    protected array $jsonPutBody = [
        'classe' => 'TESTE PUT',
        'objetoId' => 312,
    ];

    protected array $jsonPatchBody = [
        'objetoId' => 1111,
    ];

    private array $jsonCheckObject = [
        'classe' => 'TESTE',
        'objetoId' => 11,
        'context' => '{"consultar objeto": true}',
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
    public function testThatCheckRouteReturn201(string $username, string $password): void
    {
        $url = $this->baseUrl.'/consultar';
        $response = $this->basicRequest($url, 'POST', $username, $password, $this->jsonCheckObject);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(201, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatCheckRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/consultar';
        $response = $this->basicRequest($url, 'POST', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }
}
