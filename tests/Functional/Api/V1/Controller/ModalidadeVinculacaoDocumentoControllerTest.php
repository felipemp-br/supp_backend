<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeVinculacaoDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeVinculacaoDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_vinculacao_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE VINCULAÇÃO DOCUMENTO - POST',
        'descricao' => 'TESTE MODALIDADE VINCULAÇÃO DOCUMENTO - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE VINCULAÇÃO DOCUMENTO - PUT',
        'descricao' => 'TESTE MODALIDADE VINCULAÇÃO DOCUMENTO - PUT',
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
