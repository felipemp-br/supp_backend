<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeRepresentanteControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRepresentanteControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_representante';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE REPRESENTANTE - POST',
        'descricao' => 'TESTE MODALIDADE REPRESENTANTE- POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE REPRESENTANTE - PUT',
        'descricao' => 'TESTE MODALIDADE REPRESENTANTE - PUT',
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
