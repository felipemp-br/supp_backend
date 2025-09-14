<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoContatoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoContatoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_contato';

    protected int $idToGet = 1;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'nome' => 'COLABORADOR',
        'descricao' => 'COLABORADOR',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'COORDENADOR',
        'descricao' => 'COORDENADOR',
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
