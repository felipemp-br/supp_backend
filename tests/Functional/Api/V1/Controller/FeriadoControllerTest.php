<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class FeriadoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FeriadoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/feriado';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'Dia de Tiradentes',
        'dataFeriado' => '2021-04-21 00:00:00',
        'ativo' => '1',
        'estado' => '1',
        'municipio' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'nome' => 'Dia do Trabalho',
        'dataFeriado' => '2021-05-01 00:00:00',
        'ativo' => '1',
        'estado' => '1',
        'municipio' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'Dia da Independência',
        'dataFeriado' => '2021-04-21 00:00:00',
        'ativo' => '1',
        'estado' => '1',
        'municipio' => '1',
        'criado_em' => '2021-09-14 13:37:58',
        'atualizado_em' => '2021-09-14 13:37:58',
        'apagado_em' => null,
    ];

    protected int $idToDelete = 2;
}
