<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class GeneroDocumentoAvulsoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoAvulsoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/genero_documento_avulso';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'TESTE GÊNERO DOCUMENTO AVULSO - POST',
        'descricao' => 'TESTE GÊNERO DOCUMENTO AVULSO - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'TESTE GÊNERO DOCUMENTO AVULSO - PUT',
        'descricao' => 'TESTE GÊNERO DOCUMENTO AVULSO - PUT',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'TESTE GÊNERO DOCUMENTO AVULSO - PATCH',
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
