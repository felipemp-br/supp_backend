<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeDestinacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeDestinacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_destinacao';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE DESTINAÇÃO - POST',
        'descricao' => 'TESTE MODALIDADE DESTINAÇÃO - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE DESTINAÇÃO - PUT',
        'descricao' => 'TESTE MODALIDADE DESTINAÇÃO - PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'TESTE MODALIDADE DESTINAÇÃO - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
