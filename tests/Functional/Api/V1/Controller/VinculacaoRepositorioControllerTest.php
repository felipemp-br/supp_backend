<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoRepositorioControllerTest.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class VinculacaoRepositorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_repositorio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'repositorio' => 1,
        'usuario' => 2,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'repositorio' => 1,
        'usuario' => 2,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'repositorio' => 2,
    ];

    protected int $idToDelete = 1;
}
