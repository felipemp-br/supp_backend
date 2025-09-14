<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeGeneroPessoaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeGeneroPessoaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_genero_pessoa';

    protected int $idToGet = 2;

    protected int $idToDelete = 1;

    protected int $idToPut = 2;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE GÊNERO PESSOA - POST',
        'descricao' => 'TESTE MODALIDADE GÊNERO PESSOA - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE GÊNERO PESSOA - PUT',
        'descricao' => 'TESTE MODALIDADE GÊNERO PESSOA - PUT',
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
