<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeTransicaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeTransicaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_transicao';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'ANEXO',
        'descricao' => 'ANEXO',
        'ativo' => true,
    ];

    protected int $idToPut = 6;

    protected array $jsonPutBody = [
        'valor' => 'ANEXO',
        'descricao' => 'ANEXO',
        'ativo' => true,
    ];

    protected int $idToPatch = 6;

    protected array $jsonPatchBody = [
        'valor' => 'ANEXO',
        'descricao' => 'ANEXO',
        'ativo' => true,
    ];

    protected int $idToDelete = 6;
}
