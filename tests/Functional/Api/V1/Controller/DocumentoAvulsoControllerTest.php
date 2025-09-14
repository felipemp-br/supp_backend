<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class DocumentoAvulsoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoAvulsoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/documento_avulso';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected array $jsonPostBody = [
        'dataHoraConclusaoPrazo' => '2022-06-15 17:00:00',
        'dataHoraInicioPrazo' => '2021-06-15 17:00:00',
        'dataHoraEncerramento' => '2022-06-21 17:00:00',
        'dataHoraFinalPrazo' => '2022-06-21 17:00:00',
        'dataHoraLeitura' => '21:00:00',
        'dataHoraReiteracao' => '21:30:00',
        'dataHoraRemessa' => '22:30:00',
        'dataHoraResposta' => '22:40:00',
        'mecanismoRemessa' => 'Mecanismo 1',
        'observacao' => 'Observação 1',
        'postIt' => 'TESTE 11',
        'urgente' => 'false',

        'documentoAvulsoOrigem' => null,
        'documentoResposta' => '2',
        'processoDestino' => '2',
        'pessoaDestino' => '2',
        'setorDestino' => '2',
        'tarefaOrigem' => '2',
        'usuarioRemessa' => '4',
        'usuarioResposta' => '4',

        'documentoRemessa' => '5',
        'especieDocumentoAvulso' => '1',
        'modelo' => '1',
        'processo' => '1',
        'setorOrigem' => '1',
        'setorResponsavel' => '1',
        'usuarioResponsavel' => '4',
        'criadoPor' => '4',
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'dataHoraConclusaoPrazo' => '2022-06-15 17:00:00',
        'dataHoraInicioPrazo' => '2021-06-15 17:00:00',
        'dataHoraEncerramento' => '2022-06-21 17:00:00',
        'dataHoraFinalPrazo' => '2022-06-21 17:00:00',
        'dataHoraLeitura' => '21:00:00',
        'dataHoraReiteracao' => '21:30:00',
        'dataHoraRemessa' => '22:30:00',
        'dataHoraResposta' => '22:40:00',
        'mecanismoRemessa' => 'Mecanismo 2',
        'observacao' => 'Observação 2',
        'postIt' => 'TESTE 12',
        'urgente' => 'false',

        'documentoAvulsoOrigem' => null,
        'documentoResposta' => '2',
        'processoDestino' => '2',
        'pessoaDestino' => '2',
        'setorDestino' => '2',
        'tarefaOrigem' => '2',
        'usuarioRemessa' => null,
        'usuarioResposta' => null,

        'documentoRemessa' => '5',
        'especieDocumentoAvulso' => '1',
        'modelo' => '1',
        'processo' => '1',
        'setorOrigem' => '1',
        'setorResponsavel' => '1',
        'usuarioResponsavel' => '4',
        'criadoPor' => '4',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'dataHoraConclusaoPrazo' => '2022-06-22 17:00:00',
        'dataHoraInicioPrazo' => '2021-06-22 17:00:00',
        'dataHoraEncerramento' => '2022-06-22 17:00:00',
        'dataHoraFinalPrazo' => '2022-06-22 17:00:00',
        'dataHoraLeitura' => '21:00:00',
        'dataHoraReiteracao' => '21:30:00',
        'dataHoraRemessa' => '22:30:00',
        'dataHoraResposta' => '22:40:00',
        'mecanismoRemessa' => 'Mecanismo 3',
        'observacao' => 'Observação 3',
        'postIt' => 'TESTE 13',
        'urgente' => 'false',

        'documentoAvulsoOrigem' => null,
        'documentoResposta' => '2',
        'processoDestino' => '2',
        'pessoaDestino' => '2',
        'setorDestino' => '2',
        'tarefaOrigem' => '2',
        'usuarioRemessa' => null,
        'usuarioResposta' => null,

        'documentoRemessa' => '5',
        'especieDocumentoAvulso' => '1',
        'modelo' => '1',
        'processo' => '1',
        'setorOrigem' => '1',
        'setorResponsavel' => '1',
        'usuarioResponsavel' => '4',
        'criadoPor' => '4',
    ];

    protected int $idToToggleLida = 1;

    protected int $idToToggleEncerramento = 1;

    /**
     * @throws Throwable
     */
    public function testThatToggleLidaPatchRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_lida', 'PATCH', null, null, []);

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
    public function testThatToggleLidaPatchRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_lida', 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatToggleLidaPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->jsonPatchBody, '$jsonPatchBody deve conter dados.');
        static::assertNotEmpty($this->idToToggleLida, '$idToToggleLida deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToToggleLida.'/toggle_lida';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonPatchBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /* -------------------------- */

    /**
     * @throws Throwable
     */
    public function testThatToggleEncerramentoPatchRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_encerramento', 'PATCH', null, null, []);

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
    public function testThatToggleEncerramentoPatchRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/toggle_encerramento', 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatToggleEncerramentoPatchRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->jsonPatchBody, '$jsonPatchBody deve conter dados.');
        static::assertNotEmpty($this->idToToggleEncerramento, '$idToToggleLida deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToToggleEncerramento.'/toggle_encerramento';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonPatchBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatRemeterPatchRouteReturn401(): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/remeter', 'PATCH', null, null, []);

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
    public function testThatRemeterPatchRouteReturn404(string $username, string $password): void
    {
        $response = $this->basicRequest($this->baseUrl.'/0/remeter', 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(404, $response->getStatusCode(), $responseJson['message']);
    }

}
