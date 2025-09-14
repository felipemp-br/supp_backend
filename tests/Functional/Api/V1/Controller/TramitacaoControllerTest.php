<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class TramitacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TramitacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tramitacao';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'processo' => 2,
        'setorOrigem' => 1,
        'setorDestino' => 2,
        'usuarioRecebimento' => 6,
        'pessoaDestino' => 3,
        'urgente' => true,
        'observacao' => 'OBSERVAÇÃO',
        'mecanismoRemessa' => 'MECANISMO DE REMESSA',
        'dataHoraRecebimento' => '2021-10-05 12:00:00',
    ];

    protected array $jsonPutBody = [
        'processo' => 4,
        'setorOrigem' => 5,
        'setorDestino' => 6,
        'setorAtual' => 3,
        'usuarioRecebimento' => 10,
        'pessoaDestino' => 3,
        'urgente' => true,
        'observacao' => 'OBSERVAÇÃO',
        'mecanismoRemessa' => 'MECANISMO DE REMESSA',
        'dataHoraRecebimento' => '2021-10-05 12:00:00',
    ];

    protected array $jsonPatchBody = [
        'urgente' => false,
    ];

    private int $idToPrintGuide = 1;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    #[RunInSeparateProcess]
    public function testThatPrintGuideRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToPrintGuide, '$idToPrintGuide deve conter o ID');

        $url = $this->baseUrl.'/imprime_guia/'.$this->idToPrintGuide;
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('conteudo', $responseJson, 'No JSON de resposta, deve conter a chave "conteudo".');
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatPrintGuideRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/imprime_guia/0';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatPrintGuideRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/imprime_guia/'.$this->idToPrintGuide,
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
}
