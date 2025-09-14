<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UsuarioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UsuarioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/usuario';

    protected int $idToGet = 2;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'username' => '12345678909',
        'nome' => 'JOSÉ USER',
        'assinatura' => 'JOSÉ USER',
        'email' => 'jose.user@test.com',
        'enabled' => true,
        'nivelAcesso' => 0,
    ];

    protected array $jsonPutBody = [
        'username' => '12345678908',
        'nome' => 'JOÃO USER',
        'assinatura' => 'JOÃO USER',
        'email' => 'joao.user@test.com',
        'enabled' => true,
        'nivelAcesso' => 0,
    ];

    protected array $jsonPatchBody = [
        'enabled' => false,
    ];

    private int $idToResetPassword = 3;

    private string $cpf = '00000000002';

    private array $jsonResetPasswordBody = [
        'context' => '{"reset de password": true}',
    ];

    private array $jsonUserValidateBody = [
        'context' => '{"validar usuário": true}',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     */
    public function testThatPostRouteReturn401(): void
    {
        static::expectNotToPerformAssertions();
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatResetPasswordRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->idToResetPassword, '$idToResetPassword deve conter o ID');

        $url = $this->baseUrl.'/'.$this->idToResetPassword.'/reseta_senha';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonResetPasswordBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatResetPasswordRouteReturn404(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/reseta_senha';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonResetPasswordBody);

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
    public function testThatUserValidateRouteReturn200(string $username, string $password): void
    {
        static::assertNotEmpty($this->cpf, '$cpf deve conter um número de CPF');

        $token = hash('SHA256', $this->cpf.(new DateTime())->format('Ymd'));

        $url = $this->baseUrl.'/'.$this->cpf.'/'.$token.'/valida_usuario';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonUserValidateBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatUserValidateRouteReturn400(string $username, string $password): void
    {
        $url = $this->baseUrl.'/0/0/valida_usuario';
        $response = $this->basicRequest($url, 'PATCH', $username, $password, $this->jsonUserValidateBody);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('message', $responseJson, 'No JSON de resposta, deve conter a chave "message".');
        static::assertSame(400, $response->getStatusCode(), $responseJson['message']);
    }
}
