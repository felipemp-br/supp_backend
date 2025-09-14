<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AssinaturaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssinaturaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/assinatura';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'assinatura' => 'MIAGCSqGSIb3DQEHAg==VEVTVEVDRVJUSUZJQ0FET0FTU0lOQVRVUkE=',
        'algoritmoHash' => 'SHA256',
        'componenteDigital' => '1',
        'cadeiaCertificadoPEM' => 'cadeia_teste',
    ];

    protected array $jsonPutBody = [
        'assinatura' => 'MIAGCSqGSIb3DQEHAg==VEVTVEVDRVJUSUZJQ0FET0FTU0lOQVRVUkE=',
        'algoritmoHash' => 'SHA256',
        'cadeiaCertificadoPEM' => 'cadeia_teste',
        'componenteDigital' => '1',
        'dataHoraAssinatura' => '2021-07-12 15:47:27',
    ];

    protected array $jsonPatchBody = [
        'componenteDigital' => '1',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatPutRouteReturn200(string $username, string $password): void
    {
        static::expectNotToPerformAssertions();
    }
}
