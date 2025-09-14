<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AvaliacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AvaliacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/avaliacao';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'objetoId' => 1,
        'avaliacao' => 75,
        'classe' => 'CLASSE TESTE',
    ];

    protected array $jsonPutBody = [
        'objetoId' => 1,
        'avaliacao' => 85,
        'classe' => 'CLASSE TESTE',
    ];

    protected array $jsonPatchBody = [
        'objetoId' => 1,
        'avaliacao' => 90,
        'classe' => 'CLASSE TESTE',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
