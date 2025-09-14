<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AreaTrabalhoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AreaTrabalhoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/area_trabalho';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'documento' => '1',
        'dono' => false,
        'usuario' => '2',
    ];

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'documento' => '2',
        'dono' => true,
        'usuario' => '2',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'dono' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testAreaTrabalho',
        ]);
        parent::setUp();
    }
}
