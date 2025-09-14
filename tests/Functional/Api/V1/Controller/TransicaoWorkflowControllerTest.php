<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TransicaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransicaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/transicao_workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'workflow' => 1,
        'especieAtividade' => 15,
        'especieTarefaFrom' => 11,
        'especieTarefaTo' => 12,
        'qtdDiasPrazo' => 5,
    ];

    protected array $jsonPutBody = [
        'workflow' => 1,
        'especieAtividade' => 14,
        'especieTarefaFrom' => 13,
        'especieTarefaTo' => 16,
        'qtdDiasPrazo' => 5,
    ];

    protected array $jsonPatchBody = [
        'especieAtividade' => 14,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
