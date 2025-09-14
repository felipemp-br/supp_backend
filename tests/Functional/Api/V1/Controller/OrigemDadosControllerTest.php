<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class OrigemDadosControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OrigemDadosControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/origem_dados';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'idExterno' => 'ID EXTERNO 1',
        'dataHoraUltimaConsulta' => '2021-09-23 12:00:00',
        'servico' => 'SERVIÇO 1',
        'fonteDados' => 'FONTE DADOS 1',
        'status' => 1,

    ];

    protected array $jsonPutBody = [
        'idExterno' => 'ID EXTERNO 2',
        'dataHoraUltimaConsulta' => '2021-09-23 16:40:00',
        'servico' => 'SERVIÇO 2',
        'fonteDados' => 'FONTE DADOS 2',
        'status' => 0,
    ];

    protected array $jsonPatchBody = [
        'idExterno' => 'ID EXTERNO 3',
        'status' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
