<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoSetorMunicipioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoSetorMunicipioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_setor_municipio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'setor' => 21,
        'municipio' => 5,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'setor' => 21,
        'municipio' => 6,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'setor' => 21,
        'municipio' => 7,
    ];

    protected int $idToDelete = 1;
}
