<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieSetorControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieSetorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_setor';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'generoSetor' => 1,
        'nome' => 'ESPÉCIE SETOR - POST',
        'descricao' => 'ESPÉCIE SETOR - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'generoSetor' => 1,
        'nome' => 'ESPÉCIE SETOR - PUT',
        'descricao' => 'ESPÉCIE SETOR - PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'ESPÉCIE SETOR - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
