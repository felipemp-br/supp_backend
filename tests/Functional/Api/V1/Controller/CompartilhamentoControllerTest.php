<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class CompartilhamentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompartilhamentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000012';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/compartilhamento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'tarefa' => '1',
        'processo' => '1',
        'usuario' => '4',
        'acessor' => false,
    ];

    protected array $jsonPutBody = [
        'tarefa' => '1',
        'processo' => '1',
        'usuario' => '10',
        'acessor' => true,
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
