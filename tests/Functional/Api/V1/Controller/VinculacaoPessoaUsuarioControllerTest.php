<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoPessoaUsuarioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoPessoaUsuarioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_pessoa_usuario';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'pessoa' => 1,
        'usuarioVinculado' => 1,
    ];

    protected array $jsonPutBody = [
        'pessoa' => 2,
        'usuarioVinculado' => 10,
    ];

    protected array $jsonPatchBody = [
        'pessoa' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
