<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeFolderControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeFolderControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_folder';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'CERTIFICAÇÃO',
        'descricao' => 'CERTIFICAÇÃO',
        'ativo' => true,
    ];

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'valor' => 'CERTIFICAÇÃO',
        'descricao' => 'CERTIFICAÇÃO',
        'ativo' => true,
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'valor' => 'CERTIFICAÇÃO',
        'descricao' => 'CERTIFICAÇÃO',
        'ativo' => true,
    ];

    protected int $idToDelete = 2;
}
