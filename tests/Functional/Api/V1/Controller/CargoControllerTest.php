<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class CargoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CargoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000008';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/cargo';

    protected int $idToGet = 2;

    protected int $idToDelete = 5;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'ARQUIVISTA',
        'descricao' => 'ARQUIVISTA',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'PROCURADOR',
        'descricao' => 'PROCURADOR',
        'ativo' => false,
    ];

    protected array $jsonPatchBody = [
        'ativo' => true,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
