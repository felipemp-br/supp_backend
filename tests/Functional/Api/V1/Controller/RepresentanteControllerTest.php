<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class RepresentanteControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RepresentanteControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/representante';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'modalidadeRepresentante' => 3,
        'interessado' => 1,
        'origemDados' => 1,
        'nome' => 'NOME - POST',
        'inscricao' => 'SP0000001B',
    ];

    protected array $jsonPutBody = [
        'modalidadeRepresentante' => 1,
        'interessado' => 1,
        'origemDados' => 1,
        'inscricao' => 'SP0000001C',
        'nome' => 'NOME - PUT',
    ];

    protected array $jsonPatchBody = [
        'modalidadeRepresentante' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
