<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoEspecieProcessoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoEspecieProcessoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_especie_processo_workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'especieProcesso' => 1,
        'workflow' => 1,
    ];

    protected array $jsonPutBody = [
        'especieProcesso' => 2,
        'workflow' => 1,
    ];

    protected array $jsonPatchBody = [
        'especieProcesso' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
