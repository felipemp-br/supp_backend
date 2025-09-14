<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeMeioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeMeioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_meio';

    protected int $idToGet = 2;

    protected int $idToDelete = 4;

    protected int $idToPut = 4;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE MEIO - POST',
        'descricao' => 'TESTE MODALIDADE MEIO- POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE MEIO - PUT',
        'descricao' => 'TESTE MODALIDADE MEIO - PUT',
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
