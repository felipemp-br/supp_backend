<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoAvisoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoAvisoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_aviso';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'aviso' => 3,
        'especieSetor' => null,
        'modalidadeOrgaoCentral' => null,
        'setor' => null,
        'unidade' => null,
        'usuario' => 4,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'aviso' => 1,
        'especieSetor' => null,
        'modalidadeOrgaoCentral' => null,
        'setor' => null,
        'unidade' => null,
        'usuario' => 4,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'aviso' => 1,
        'especieSetor' => null,
        'modalidadeOrgaoCentral' => null,
        'setor' => null,
        'unidade' => null,
        'usuario' => 4,
    ];

    protected int $idToDelete = 1;
}
