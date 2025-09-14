<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class GeneroProcessoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroProcessoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/genero_processo';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'nome' => 'TESTE GÊNERO PROCESS0 - POST',
        'descricao' => 'TESTE GÊNERO PROCESS0 - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'TESTE GÊNERO PROCESS0 - PUT',
        'descricao' => 'TESTE GÊNERO PROCESS0 - PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'TESTE GÊNERO PROCESS0 - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
