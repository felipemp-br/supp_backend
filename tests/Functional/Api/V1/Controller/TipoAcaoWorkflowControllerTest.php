<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoAcaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoAcaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_acao_workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'trigger' => 'SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow\Trigger0005',
        'valor' => 'VINCULAÇÃO',
        'descricao' => 'VINCULAÇÃO DE PROCESSOS',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'trigger' => 'SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow\Trigger0002',
        'valor' => ' DISTRIBUIÇÃO AUTOMÁTICA',
        'descricao' => 'DISTRIBUI AS TAREFAS DE FORMA AUTOMÁTICA',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'ativo' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
