<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeFaseControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeFaseControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_fase';

    protected int $idToGet = 2;

    protected int $idToDelete = 5;

    protected int $idToPut = 5;

    protected int $idToPatch = 5;

    protected array $jsonPostBody = [
        'valor' => 'INICIAL',
        'descricao' => 'INICIAL',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'FINAL',
        'descricao' => 'FINAL',
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
