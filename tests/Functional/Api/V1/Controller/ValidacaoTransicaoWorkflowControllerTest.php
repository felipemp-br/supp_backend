<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ValidacaoTransicaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ValidacaoTransicaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/validacao_transicao_workflow';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'contexto' => 'Contexto 2',
        'metodo' => 'Metodo 2',
        'tipoValidacaoWorkflow' => 1,
        'transicaoWorkflow' => 10,
    ];

    protected array $jsonPutBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'contexto' => 'Contexto 2',
        'metodo' => 'Metodo 2',
        'tipoValidacaoWorkflow' => 1,
        'transicaoWorkflow' => 10,
    ];

    protected array $jsonPatchBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'contexto' => 'Contexto 2',
        'metodo' => 'Metodo 2',
        'tipoValidacaoWorkflow' => 1,
        'transicaoWorkflow' => 10,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
