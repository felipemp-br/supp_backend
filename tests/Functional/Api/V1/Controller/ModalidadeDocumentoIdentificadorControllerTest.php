<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeDocumentoIdentificadorControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeDocumentoIdentificadorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_documento_identificador';

    protected int $idToGet = 5;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE DOCUMENTO IDENTIFICADOR - POST',
        'descricao' => 'TESTE MODALIDADE DOCUMENTO IDENTIFICADOR - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE DOCUMENTO IDENTIFICADOR - PUT',
        'descricao' => 'TESTE MODALIDADE DOCUMENTO IDENTIFICADOR - PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'TESTE MODALIDADE DOCUMENTO IDENTIFICADOR - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
