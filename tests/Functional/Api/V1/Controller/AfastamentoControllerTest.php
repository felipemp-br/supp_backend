<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AfastamentoControllerTest.
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class AfastamentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'LoadAfastamentoData',
        ]);
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/afastamento';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'modalidadeAfastamento' => '2',
        'colaborador' => '1',
        'dataInicio' => '2020-03-06 12:59:35',
        'dataInicioBloqueio' => '2020-03-06 12:59:35',
        'dataFim' => '2020-03-06 12:59:35',
        'dataFimBloqueio' => '2020-03-06 12:59:35',
    ];

    protected int $idToPut = 3;

    protected array $jsonPutBody = [
        'modalidadeAfastamento' => '2',
        'colaborador' => '2',
        'dataInicio' => '2020-02-03 12:59:35',
        'dataInicioBloqueio' => '2020-02-03 12:59:35',
        'dataFim' => '2020-02-10 12:59:35',
        'dataFimBloqueio' => '2020-02-10 12:59:35',
    ];

    protected int $idToPatch = 3;

    protected array $jsonPatchBody = [
        'modalidadeAfastamento' => '3',
        'colaborador' => '2',
    ];

    protected int $idToDelete = 3;
}
