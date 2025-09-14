<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoValidacaoWorkflowControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoValidacaoWorkflowControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_validacao_workflow';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'SETOR DE DESTINO',
        'descricao' => 'SETOR DE DESTINO',
        'sigla' => 'SETOR_DTN',
        'ativo' => true,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'valor' => 'SETOR DE DESTINO',
        'descricao' => 'SETOR DE DESTINO',
        'sigla' => 'SETOR_DTN',
        'ativo' => true,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'valor' => 'SETOR DE DESTINO',
        'descricao' => 'SETOR DE DESTINO',
        'sigla' => 'SETOR_DTN',
        'ativo' => true,
    ];

    protected int $idToDelete = 1;
}
