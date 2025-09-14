<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class DesentranhamentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DesentranhamentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/desentranhamento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'processoDestino' => 1,
        'juntada' => 2,
        'tipo' => 'novo_processo',
        'observacao' => 'observacao',
    ];

    protected array $jsonPutBody = [
        'processoDestino' => 2,
        'juntada' => 1,
        'tipo' => 'novo_processo',
        'observacao' => 'observacao',
    ];

    protected array $jsonPatchBody = [
        'juntada' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'LoadDesentranhamentoData',
        ]);
        parent::setUp();
    }
}
