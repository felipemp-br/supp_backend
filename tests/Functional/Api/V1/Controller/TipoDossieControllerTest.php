<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoDossieControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDossieControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_dossie';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 1;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'periodoGuarda' => 50,
        'nome' => 'CRIMINAL',
        'descricao' => 'DOSSIÊ CRIMINAL',
        'sigla' => 'CRI',
        'fonteDados' => 'FONTE DE DADOS',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'periodoGuarda' => 10,
        'nome' => 'ADMINISTRATIVO',
        'descricao' => 'DOSSIÊ ADMINISTRATIVO',
        'sigla' => 'ADM',
        'fonteDados' => 'FONTE DE DADOS',
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
