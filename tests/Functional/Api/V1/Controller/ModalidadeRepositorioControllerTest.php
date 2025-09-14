<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeRepositorioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeRepositorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_repositorio';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'PROCESSO',
        'descricao' => 'Descrição do PROCESSO',
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'valor' => 'PROCESSO',
        'descricao' => 'Descrição do PROCESSO',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'valor' => 'PROCESSO',
        'descricao' => 'Descrição do PROCESSO',
    ];

    protected int $idToDelete = 1;
}
