<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class LembreteControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LembreteControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/lembrete';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected int $idToPatch = 3;

    protected array $jsonPostBody = [
        'processo' => 2,
        'conteudo' => 'LEMBRETE A1',
    ];

    protected array $jsonPutBody = [
        'processo' => 1,
        'conteudo' => 'LEMBRETE B2',
    ];

    protected array $jsonPatchBody = [
        'processo' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testLembrete',
        ]);
        parent::setUp();
    }
}
