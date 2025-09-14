<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class CronjobControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CronjobControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/cronjob';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'TESTE',
        'descricao' => 'TESTE',
        'sincrono' => true,
        'periodicidade' => '* */1 * * *',
        'comando' => 'echo cronjob teste',
    ];

    protected array $jsonPutBody = [
        'nome' => 'TESTE',
        'descricao' => 'TESTE',
        'sincrono' => false,
        'periodicidade' => '* */5 * * *',
        'comando' => 'echo cronjob teste',
    ];

    protected array $jsonPatchBody = [
        'periodicidade' => '* */12 * * *',
    ];

    private int $idToStart = 1;

    private array $context = [
        'context' => '{"test":"true"}',
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
    public function testThatStartCronJobRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToStart, '$idToStart deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToStart.'/start_job';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->context);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertSame(1, $responseJson['statusUltimaExecucao'], 'Status da última execução deveria ser igual a 1');
    }

    /**
     * @throws Throwable
     */
    public function testThatStartCronJobRouteReturn401(): void
    {
        static::assertNotEmpty($this->idToStart, '$idToStart deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToStart.'/start_job';
        $response = $this->basicRequest($url, 'PATCH', null, null, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(401, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatStartCronJobRouteReturn404(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToStart, '$idToStart deve conter o ID');

        $url = $this->baseUrl.'/0/start_job';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(404, $response->getStatusCode());
    }
}
