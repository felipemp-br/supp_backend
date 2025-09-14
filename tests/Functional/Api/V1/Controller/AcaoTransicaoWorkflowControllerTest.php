<?php

declare(strict_types=1);
/**
 * /tests/Functional/Controller/AcaoTransicaoWorkflowControllerTest.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AcaoTransicaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcaoTransicaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/acao_transicao_workflow';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'transicaoWorkflow' => 5,
        'tipoAcaoWorkflow' => 3,
    ];

    protected int $idToDelete = 4;

    protected int $idToPut = 3;

    protected array $jsonPutBody = [
        'transicaoWorkflow' => 10,
        'tipoAcaoWorkflow' => 3,
    ];

    protected int $idToPatch = 5;

    protected array $jsonPatchBody = [
        'tipoAcaoWorkflow' => 3,
    ];
}
