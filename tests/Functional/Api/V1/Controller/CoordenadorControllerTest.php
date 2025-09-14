<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class CoordenadorControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CoordenadorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/coordenador';

    protected int $idToGet = 3;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'setor' => '4',
        'unidade' => null,
        'orgaoCentral' => null,
        'usuario' => '5',
    ];

    protected array $jsonPutBody = [
        'setor' => null,
        'unidade' => '1',
        'orgaoCentral' => null,
        'usuario' => '7',
    ];

    protected array $jsonPatchBody = [
        'usuario' => '11',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
