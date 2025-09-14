<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ConfiguracaoNupControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfiguracaoNupControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/configuracao_nup';

    protected int $idToGet = 2;

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'dataHoraInicioVigencia' => '2020-01-01 00:00:00',
        'nome' => 'NUP DO PODER EXECUTIVO FEDERAL DE 13 DÍGITOS',
        'descricao' => 'NÚMERO ÚNICO DE PROTOCOLO DO PODER EXECUTIVO FEDERAL DE 13 DÍGITOS',
        'sigla' => 'NUPEXEC13',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'dataHoraInicioVigencia' => '2020-01-01 00:00:00',
        'nome' => 'NUP DO PODER EXECUTIVO FEDERAL DE 11 DÍGITOS',
        'descricao' => 'NÚMERO ÚNICO DE PROTOCOLO DO PODER EXECUTIVO FEDERAL DE 11 DÍGITOS',
        'sigla' => 'NUPEXEC11',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'dataHoraFimVigencia' => '2025-01-01 00:00:00',
    ];

    private int $unidadeArquivistica = 1;

    private int $configuracaoNup = 2;

    private string $nup = '00004800000182082';

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatValidateNupRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->unidadeArquivistica, '$unidadeArquivistica deve conter o ID');
        static::assertNotEmpty($this->configuracaoNup, '$configuracaoNup deve conter o ID');
        static::assertNotEmpty($this->nup, '$nup deve conter o ID');

        $url = $this->baseUrl.'/'.$this->configuracaoNup.'/validar_nup/'.$this->nup.'/'.$this->unidadeArquivistica;
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatValidateNupRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/validar_nup/0/0';
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
    public function testThatValidateNupRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->configuracaoNup.'/validar_nup/'.$this->nup.'/'.$this->unidadeArquivistica,
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
