<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeAfastamentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeAfastamentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_afastamento';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'AFASTAMENTO PAIS',
        'descricao' => 'AFASTAMENTO PAIS',
        'ativo' => true,
        'criado_em' => '2021-09-20 09:37:58',
        'atualizado_em' => '2021-09-20 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'valor' => 'AFASTAMENTO SAÚDE',
        'descricao' => 'AFASTAMENTO SAÚDE',
        'ativo' => true,
        'criado_em' => '2021-09-20 09:37:58',
        'atualizado_em' => '2021-09-20 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'valor' => 'LICENÇA PATERNIDADE',
        'descricao' => 'LICENÇA PATERNIDADE',
        'ativo' => true,
        'criado_em' => '2021-09-20 09:37:58',
        'atualizado_em' => '2021-09-20 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToDelete = 1;
}
