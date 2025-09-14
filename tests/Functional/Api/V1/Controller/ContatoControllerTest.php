<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ContatoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ContatoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/contato';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'setor' => '7',
        'grupoContato' => '1',
        'usuario' => '6',
        'tipoContato' => '3',
    ];

    protected array $jsonPutBody = [
        'setor' => '5',
        'grupoContato' => '1',
        'usuario' => '2',
        'tipoContato' => '2',
    ];

    protected array $jsonPatchBody = [
        'usuario' => '11',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
