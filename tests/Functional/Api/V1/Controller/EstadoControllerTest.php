<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class DocumentoAvulsoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EstadoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/estado';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'Estado 1',
        'uf' => 'E1',
        'pais' => '31',
        'criado_em' => '07/09/2021 04:54:12',
        'atualizado_em' => '07/09/2021 04:54:12',
        'apagado_em' => null,
        'ativo' => '1',

    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'nome' => 'Estado 2',
        'uf' => 'E2',
        'pais' => '31',
        'criado_em' => '07/09/2021 04:54:12',
        'atualizado_em' => '07/09/2021 04:54:12',
        'apagado_em' => null,
        'ativo' => '1',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'Estado 3',
        'uf' => 'E3',
        'pais' => '31',
        'criado_em' => '07/09/2021 04:54:12',
        'atualizado_em' => '07/09/2021 04:54:12',
        'apagado_em' => null,
        'ativo' => '1',
    ];

    protected int $idToDelete = 27;
}
