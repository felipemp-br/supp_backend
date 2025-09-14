<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoSigiloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoSigiloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_sigilo';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'nivelAcesso' => 4,
        'prazoAnos' => 100,
        'leiAcessoInformacao' => true,
        'nome' => 'TIPO SIGILO - POST',
        'descricao' => 'DESCRIÇÃO TIPO SIGILO - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nivelAcesso' => 4,
        'prazoAnos' => 100,
        'leiAcessoInformacao' => true,
        'nome' => 'TIPO SIGILO - PUT',
        'descricao' => 'DESCRIÇÃO TIPO SIGILO - PUT',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'prazoAnos' => 10,
        'nivelAcesso' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
