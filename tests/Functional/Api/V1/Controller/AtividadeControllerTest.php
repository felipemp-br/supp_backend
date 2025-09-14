<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AtividadeControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AtividadeControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/atividade';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'observacao' => 'TESTE_XPTO',
        'dataHoraConclusao' => '2022-12-31 18:00:00',
        'encerraTarefa' => false,
        'destinacaoMinutas' => 'TESTE_2',
        'especieAtividade' => '1',
        'tarefa' => '1',
        'usuario' => '2',
    ];

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'observacao' => 'TESTE_XPTO_111',
        'dataHoraConclusao' => '2022-12-31 18:00:00',
        'encerraTarefa' => false,
        'destinacaoMinutas' => 'TESTE_2111111111',
        'especieAtividade' => '1',
        'tarefa' => '1',
        'usuario' => '2',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'dataHoraConclusao' => '2022-03-27 21:30:13',
    ];
}
