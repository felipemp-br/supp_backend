<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class RelatorioControllerTest.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelatorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/relatorio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'conteudoHTML' => 'conteudoHTML',
        'observacao' => 'observacao',
        'tipoRelatorio' => 1,
        'dataHoraInicio' => '2020-03-27 18:00:00',
        'dataHoraFinal' => '2020-03-27 20:00:13',
        'documento' => 1,
    ];

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'conteudoHTML' => 'conteudoHTML',
        'observacao' => 'observacao',
        'tipoRelatorio' => 2,
        'dataHoraInicio' => '2020-03-27 18:00:00',
        'dataHoraFinal' => '2020-03-27 20:00:13',
        'documento' => 2,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'tipoRelatorio' => 1,
        'documento' => 3,
        'dataHoraFinal' => '2020-03-27 21:30:13',
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
    public function testThatCreateMyTasksReportRouteReturn200(string $username, string $password): void
    {
        $url = $this->baseUrl.'/gerar_relatorio_minhas_tarefas';
        $response = $this->basicRequest($url, 'POST', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatCreateMyTasksReportRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/gerar_relatorio_minhas_tarefas',
            'POST',
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
}
