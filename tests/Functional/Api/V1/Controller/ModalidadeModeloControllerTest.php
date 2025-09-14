<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeModeloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeModeloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_modelo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'INTERNACIONAL',
        'descricao' => 'INTERNACIONAL',
        'ativo' => true,
    ];

    protected int $idToPut = 5;

    protected array $jsonPutBody = [
        'valor' => 'INTERNACIONAL',
        'descricao' => 'INTERNACIONAL',
        'ativo' => true,
    ];

    protected int $idToPatch = 5;

    protected array $jsonPatchBody = [
        'valor' => 'INTERNACIONAL',
        'descricao' => 'INTERNACIONAL',
        'ativo' => true,
    ];

    protected int $idToDelete = 5;
}
