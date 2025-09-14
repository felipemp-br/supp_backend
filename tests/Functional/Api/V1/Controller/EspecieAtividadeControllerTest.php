<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieAtividadeControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieAtividadeControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_atividade';

    protected int $idToGet = 10;

    protected int $idToDelete = 11;

    protected int $idToPut = 12;

    protected int $idToPatch = 13;

    protected array $jsonPostBody = [
        'generoAtividade' => 1,
        'descricao' => 'DESCRIÇÃO ESPÉCIE ATIVIDADE - POST',
        'nome' => ' ESPÉCIE ATIVIDADE #POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'generoAtividade' => 2,
        'descricao' => 'DESCRIÇÃO ESPÉCIE ATIVIDADE - PUT',
        'nome' => ' ESPÉCIE ATIVIDADE #PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'DESCRIÇÃO ESPÉCIE ATIVIDADE - PATCH',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
