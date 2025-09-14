<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AvisoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AvisoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/aviso';

    protected int $idToGet = 3;

    protected array $jsonPostBody = [
        'nome' => 'User Post Body',
        'descricao' => 'User Post - Observação',
        'sistema' => true,
        'ativo' => true,
    ];

    protected int $idToDelete = 5;

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'nome' => 'User Put Body',
        'descricao' => 'User Put - Observação',
        'sistema' => true,
        'ativo' => true,
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'sistema' => false,
    ];
}
