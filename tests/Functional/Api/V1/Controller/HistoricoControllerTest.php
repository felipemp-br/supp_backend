<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class HistoricoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class HistoricoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/historico';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'processo' => 2,
        'descricao' => 'HISTÓRICO PROCESSO 2',
    ];

    protected array $jsonPutBody = [
        'processo' => 1,
        'descricao' => 'HISTÓRICO PROCESSO 1',
    ];

    protected array $jsonPatchBody = [
        'processo' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testHistorico',
        ]);
        parent::setUp();
    }
}
