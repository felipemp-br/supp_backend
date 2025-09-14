<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'tarefaInicial' => 2,
        'tarefaAtual' => 3,
        'workflow' => 1,
        'concluido' => false,
        'transicaoFinalWorkflow' => false,
    ];

    protected array $jsonPutBody = [
        'tarefaInicial' => 3,
        'tarefaAtual' => 3,
        'workflow' => 1,
        'concluido' => false,
        'transicaoFinalWorkflow' => false,
    ];

    protected array $jsonPatchBody = [
        'concluido' => true,
        'transicaoFinalWorkflow' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
