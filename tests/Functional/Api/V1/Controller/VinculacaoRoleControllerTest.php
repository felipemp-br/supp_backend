<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoRoleControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoRoleControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_role';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected array $jsonPostBody = [
        'usuario' => 11,
        'role' => 'ROLE_ADMIN',
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

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function testThatPutRouteReturn401(): void
    {
        static::expectNotToPerformAssertions();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatPutRouteReturn404(string $username, string $password): void
    {
        static::expectNotToPerformAssertions();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatPatchRouteReturn200(string $username, string $password): void
    {
        static::expectNotToPerformAssertions();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function testThatPatchRouteReturn401(): void
    {
        static::expectNotToPerformAssertions();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    #[DataProvider('usernamePasswordProvider')]
    public function testThatPatchRouteReturn404(string $username, string $password): void
    {
        static::expectNotToPerformAssertions();
    }
}
