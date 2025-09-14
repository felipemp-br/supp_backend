<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ContaEmailControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ContaEmailControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/conta_email';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'setor' => 1,
        'servidorEmail' => 1,
        'login' => 'SUPP',
        'senha' => 'SUPP',
        'nome' => 'SUPP',
        'descricao' => 'SUPP',
    ];

    protected array $jsonPutBody = [
        'setor' => 2,
        'servidorEmail' => 1,
        'login' => 'SUPP',
        'senha' => 'SUPP',
        'nome' => 'SUPP',
        'descricao' => 'SUPP',
    ];

    protected array $jsonPatchBody = [
        'setor' => 3,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
