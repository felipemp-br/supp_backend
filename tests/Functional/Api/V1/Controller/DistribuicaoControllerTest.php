<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class DocumentoAvulsoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuicaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/distribuicao';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected array $jsonPostBody = [
        'livreBalanceamento' => false,
        'distribuicaoAutomatica' => true,
        'dataHoraDistribuicao' => '2021-09-01 09:00:00',
        'tarefa' => '2',
        'usuarioAnterior' => '1',
        'usuarioPosterior' => '10',
        'setorAnterior' => '1',
        'setorPosterior' => '1',
        'tipoDistribuicao' => 1,
        'auditoriaDistribuicao' => 'Auditoria 1',
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'livreBalanceamento' => false,
        'distribuicaoAutomatica' => true,
        'dataHoraDistribuicao' => '2021-09-01 09:00:00',
        'tarefa' => '2',
        'usuarioAnterior' => 1,
        'usuarioPosterior' => '10',
        'setorAnterior' => '1',
        'setorPosterior' => '1',
        'tipoDistribuicao' => 1,
        'auditoriaDistribuicao' => 'Auditoria 2',
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'livreBalanceamento' => false,
        'distribuicaoAutomatica' => true,
        'dataHoraDistribuicao' => '2021-09-01 09:00:00',
        'tarefa' => '2',
        'usuarioAnterior' => '8',
        'usuarioPosterior' => '10',
        'setorAnterior' => '1',
        'setorPosterior' => '2',
        'tipoDistribuicao' => 1,
        'auditoriaDistribuicao' => 'Auditoria 3',
    ];
}
