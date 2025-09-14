<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class RelacionamentoPessoalControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelacionamentoPessoalControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/relacionamento_pessoal';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'pessoa' => 6,
        'pessoaRelacionada' => 2,
        'modalidadeRelacionamentoPessoal' => 3,
        'origemDados' => 1,
    ];

    protected array $jsonPutBody = [
        'pessoa' => 2,
        'pessoaRelacionada' => 7,
        'modalidadeRelacionamentoPessoal' => 1,
        'origemDados' => 1,
    ];

    protected array $jsonPatchBody = [
        'modalidadeRelacionamentoPessoal' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
