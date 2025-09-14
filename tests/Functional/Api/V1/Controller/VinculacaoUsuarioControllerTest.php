<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoUsuarioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoUsuarioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_usuario';

    protected int $idToGet = 2;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'usuario' => 3,
        'usuarioVinculado' => 9,
        'criaOficio' => true,
        'criaMinuta' => true,
        'compartilhaTarefa' => true,
        'encerraTarefa' => true,
    ];

    protected array $jsonPutBody = [
        'usuario' => 2,
        'usuarioVinculado' => 9,
        'criaOficio' => true,
        'criaMinuta' => true,
        'compartilhaTarefa' => true,
        'encerraTarefa' => true,
    ];

    protected array $jsonPatchBody = [
        'compartilhaTarefa' => false,
        'encerraTarefa' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
