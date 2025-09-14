<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class GeneroTarefaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroTarefaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/genero_tarefa';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'COMERCIAL',
        'descricao' => 'COMERCIAL',
        'ativo' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'nome' => 'ADMINISTRATIVO',
        'descricao' => 'ADMINISTRATIVO',
        'ativo' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'ADMINISTRATIVO',
        'descricao' => 'ADMINISTRATIVO',
        'ativo' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToDelete = 3;
}
