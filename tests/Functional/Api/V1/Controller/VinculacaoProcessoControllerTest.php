<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoProcessoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoProcessoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_processo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'processo' => 1,
        'observacao' => 'Observação 1',
        'processoVinculado' => 5,
        'modalidadeVinculacaoProcesso' => 1,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'processo' => 1,
        'observacao' => 'Observação 1',
        'processoVinculado' => 5,
        'modalidadeVinculacaoProcesso' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'processo' => 1,
        'observacao' => 'Observação 1',
        'processoVinculado' => 5,
        'modalidadeVinculacaoProcesso' => 1,
    ];

    protected int $idToDelete = 1;
}
