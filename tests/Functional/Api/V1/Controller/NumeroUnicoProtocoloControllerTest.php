<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class NumeroUnicoProtocoloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroUnicoProtocoloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/numero_unico_protocolo';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'setor' => 2,
        'sequencia' => 10,
        'prefixoNUP' => '10010',
        'ano' => 2021,
    ];

    protected array $jsonPutBody = [
        'setor' => 5,
        'sequencia' => 100,
        'prefixoNUP' => '10010',
        'ano' => 2021,
    ];

    protected array $jsonPatchBody = [
        'sequencia' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
