<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ColaboradorControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ColaboradorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/colaborador';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'cargo' => 1,
        'modalidadeColaborador' => 2,
        'usuario' => 10,
        'ativo' => 1,
    ];

    protected int $idToDelete = 4;

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'cargo' => 2,
        'modalidadeColaborador' => 3,
        'usuario' => 2,
        'ativo' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'cargo' => 5,
        'modalidadeColaborador' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
