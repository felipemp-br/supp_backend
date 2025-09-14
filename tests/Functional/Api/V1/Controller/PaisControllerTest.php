<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class PaisControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PaisControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/pais';

    protected int $idToGet = 1;

    protected int $idToDelete = 10;

    protected int $idToPut = 20;

    protected int $idToPatch = 30;

    protected array $jsonPostBody = [
        'codigo' => 'WW',
        'nome' => 'TESTE PAÍS - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'codigo' => 'WW',
        'nome' => 'TESTE PAÍS - PUT',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'ativo' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
