<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class FolderControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FolderControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/folder';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'modalidadeFolder' => 1,
        'usuario' => 2,
        'nome' => 'FOLDER TESTE - POST',
        'descricao' => 'FOLDER  TESTE - POST',
    ];

    protected array $jsonPutBody = [
        'modalidadeFolder' => 1,
        'usuario' => 1,
        'nome' => 'FOLDER TESTE - PUT',
        'descricao' => 'FOLDER  TESTE - PUT',
    ];

    protected array $jsonPatchBody = [
        'nome' => 'FOLDER TESTE - PATCH',
        'descricao' => 'FOLDER  TESTE - PATCH',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
