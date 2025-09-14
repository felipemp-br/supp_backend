<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ConfigModuloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ConfigModuloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/config_modulo';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'modulo' => 1,
        'nome' => 'supp_core.administrativo_backend.teste',
        'descricao' => 'TESTE',
        'setInvalid' => false,
        'mandatory' => true,
        'dataType' => 'json',
        'dataSchema' => '{"title":"teste","id":"supp_core.administrativo_backend.teste"}',
    ];

    protected array $jsonPutBody = [
        'modulo' => 1,
        'nome' => 'supp_core.administrativo_backend.teste',
        'descricao' => 'TESTE',
        'setInvalid' => false,
        'mandatory' => true,
        'dataType' => 'json',
        'dataSchema' => '{"title":"teste","id":"supp_core.administrativo_backend.teste"}',
        'context' => '{"edit-admin":"true"}',
    ];

    protected array $jsonPatchBody = [
        'mandatory' => false,
        'context' => '{"edit-admin":"true"}',
    ];

    private string $schemaName = 'supp_core.administrativo_backend.processo.template';

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatFindBySchemaNameRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->schemaName, 'schemaName deve conter uma string');

        $url = $this->baseUrl.'/schema/'.$this->schemaName;
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('$schema', $responseJson, 'No JSON de resposta, deve conter a chave "$schema".');
        static::assertArrayHasKey('$id', $responseJson, 'No JSON de resposta, deve conter a chave "$id".');
        static::assertArrayHasKey('title', $responseJson, 'No JSON de resposta, deve conter a chave "title".');
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatFindBySchemaNameRouteReturn400(string $username, string $password): void
    {
        static::assertNotEmpty($this->schemaName, 'schemaName deve conter uma string');

        $url = $this->baseUrl.'/schema/test';
        $response = $this->basicRequest($url, 'GET', $username, $password, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(400, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testThatFindBySchemaNameRouteReturn401(): void
    {
        static::assertNotEmpty($this->schemaName, 'schemaName deve conter uma string');

        $url = $this->baseUrl.'/schema/'.$this->schemaName;
        $response = $this->basicRequest($url, 'GET', null, null, []);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(401, $response->getStatusCode());
    }
}
