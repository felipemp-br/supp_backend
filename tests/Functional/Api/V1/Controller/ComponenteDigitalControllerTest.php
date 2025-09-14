<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ComponenteDigitalControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ComponenteDigitalControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/componente_digital';

    protected int $idToGet = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected int $idToDelete = 5;

    protected array $jsonPostBody = [
        'fileName' => 'OFÍCIO.html',
        'extensao' => 'html',
        'tamanho' => '17',
        'mimetype' => 'text/html',
        'conteudo' => '<p>TESTE POST</p>',
        'documento' => '5',
        'hash' => '8481069ba8b28be29b5613f3f7653e830beed824d22deefc1602fcd48cbc0cd2',
    ];

    protected array $jsonPutBody = [
        'fileName' => 'OFÍCIO.html',
        'extensao' => 'html',
        'tamanho' => '16',
        'mimetype' => 'text/html',
        'conteudo' => '<p>TESTE PUT</p>',
        'numeracaoSequencial' => '1',
        'hash' => '76eb9a268f7dfabbd0029634a5f27f8bf623cec251088ed1b3efba5ccdf0a661',
        'hashAntigo' => '209d38d14b760c0d450953dd38e43b4a0e3374b02684b8e786edb718e88aa5e1',
        'nivelComposicao' => '1',
        'documento' => '1',
    ];

    protected array $jsonPatchBody = [
        'conteudo' => '<p>TESTE PUT</p>',
        'documento' => '5',
        'hash' => '76eb9a268f7dfabbd0029634a5f27f8bf623cec251088ed1b3efba5ccdf0a661',
    ];

    private int $idToUndelete = 6;

    private int $idToDownload = 7;

    private int $idToConvert = 4;

    private int $idToRevert = 2;

    private int $idToDownloadP7s = 1;

    private array $jsonToApproveBody = [
        'documentoOrigem' => 7,
    ];

    private array $jsonToRevertBody = [
        'hash' => '209d38d14b760c0d450953dd38e43b4a0e3374b02684b8e786edb718e88aa5e1',
    ];

    private array $jsonToCompareVersion = [
        'context' => '{"compararVersao":"9bf13af53cebc56087cb863fdf72be5eb47b4a95908c6450be816a7c228f01ca"}',
    ];

    public static function setUpBeforeClass(): void
    {
        (new ComponenteDigitalControllerTest('ComponenteDigitalControllerTest'))->restoreDatabase();
        parent::setUpBeforeClass();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatDownloadRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToDownload, '$idToDownload deve conter o ID');
        static::assertIsArray($this->jsonToCompareVersion, '$jsonToCompareVersion deve conter um array');

        $url = $this->baseUrl.'/'.$this->idToDownload.'/download';
        $response = $this->basicRequest($url, 'GET', $username, $password, $this->jsonToCompareVersion);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('conteudo', $responseJson, 'No JSON de resposta, deve conter a chave "conteudo".');
    }

    /**
     * @throws Throwable
     */
    public function testThatDownloadRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToDownload.'/download',
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
    public function testThatDownloadRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/download';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

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
    public function testThatConvertPdfRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToConvert, '$idToConvert deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToConvert.'/convertToPdf';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey(
            'conteudo',
            $responseJson['entities'][0],
            'No JSON de resposta, deve conter a chave "conteudo".'
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatConvertPdfRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToConvert.'/convertToPdf',
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
    public function testThatConvertHtmlRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToConvert, '$idToConvert deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToConvert.'/convertToHtml';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey(
            'conteudo',
            $responseJson['entities'][0],
            'No JSON de resposta, deve conter a chave "conteudo".'
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatConvertHtmlRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToConvert.'/convertToHtml',
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
    public function testThatConvertHtmlRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/x/convertToHtml';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

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
    public function testThatDownloadP7sRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToDownloadP7s, '$idToDownloadP7s deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToDownloadP7s.'/download_p7s';
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
    public function testThatDownloadP7sRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToDownloadP7s.'/download_p7s',
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
    public function testThatDownloadP7sRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/download_p7s';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

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
    public function testThatRevertRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToRevert, '$idToRevert deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToRevert.'/reverter';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonToRevertBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatRevertRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToDownload.'/reverter',
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
    public function testThatRevertRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/reverter';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonToRevertBody);

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
    public function testThatUndeleteRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToUndelete, '$idToUndelete deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToUndelete.'/undelete';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatUndeleteRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/'.$this->idToUndelete.'/undelete',
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
    public function testThatUndeleteRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/x/undelete';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, []);

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
    public function testThatApproveRouteReturn201(string $username, string $password): void
    {
        $url = $this->baseUrl.'/aprovar';
        $response = $this->basicRequest($url, 'POST', $username, $password, $this->jsonToApproveBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(201, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatApproveRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/aprovar';
        $response = $this->basicRequest($url, 'POST', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }

    /**
     * @throws Throwable
     */
    public function testThatApproveRouteReturn401(): void
    {
        $response = $this->basicRequest(
            $this->baseUrl.'/aprovar',
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
