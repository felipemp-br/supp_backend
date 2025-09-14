<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeCopiaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeCopiaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_copia';

    protected int $idToGet = 2;

    protected int $idToDelete = 4;

    protected int $idToPut = 4;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'valor' => 'TESTE POST',
        'descricao' => 'DESCRIÇÃO TESTE POST',
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE PUT',
        'descricao' => 'DESCRIÇÃO TESTE PUT',
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'DESCRIÇÃO TESTE PATCH',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
