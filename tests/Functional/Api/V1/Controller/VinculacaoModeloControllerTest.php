<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoModeloControllerTest.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class VinculacaoModeloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_modelo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'modelo' => 6,
        'usuario' => 6,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'modelo' => 6,
        'usuario' => 6,
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'usuario' => 1,
    ];

    protected int $idToDelete = 3;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testModelo',
        ]);
        parent::setUp();
    }
}
