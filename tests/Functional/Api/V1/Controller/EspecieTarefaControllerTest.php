<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieTarefaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieTarefaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_tarefa';

    protected int $idToGet = 1;

    protected int $idToDelete = 4;

    protected int $idToPut = 5;

    protected int $idToPatch = 8;

    protected array $jsonPostBody = [
        'generoTarefa' => 2,
        'nome' => 'ESPÉCIE TAREFA - POST',
        'descricao' => 'ESPÉCIE TAREFA - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'generoTarefa' => 1,
        'nome' => 'ESPÉCIE TAREFA - PUT',
        'descricao' => 'ESPÉCIE TAREFA - PUT',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'ESPÉCIE TAREFA - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
