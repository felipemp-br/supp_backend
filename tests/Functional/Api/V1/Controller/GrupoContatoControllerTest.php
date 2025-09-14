<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class GrupoContatoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GrupoContatoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000001';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/grupo_contato';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'GRUPO CONTATO - POST',
        'descricao' => 'GRUPO CONTATO - POST',
        'usuario' => 2,
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'GRUPO CONTATO - PUT',
        'descricao' => 'GRUPO CONTATO - PUT',
        'usuario' => 3,
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'ativo' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
