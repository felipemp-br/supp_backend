<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeAcaoEtiquetaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeAcaoEtiquetaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_acao_etiqueta';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'modalidadeEtiqueta' => 1,
        'valor' => 'VALOR-2',
        'descricao' => 'DESCRICAO-2',
        'trigger' => 'MODALIDADEACAOETIQUETA-TRIGGER-2',
        'ativo' => true,
        'criado_em' => '2021-09-16 09:37:58',
        'atualizado_em' => '2021-09-16 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'modalidadeEtiqueta' => 1,
        'valor' => 'VALOR-3',
        'descricao' => 'DESCRICAO-3',
        'trigger' => 'MODALIDADEACAOETIQUETA-TRIGGER-3',
        'ativo' => true,
        'criado_em' => '2021-09-16 09:37:58',
        'atualizado_em' => '2021-09-16 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'modalidadeEtiqueta' => 1,
        'valor' => 'VALOR-4',
        'descricao' => 'DESCRICAO-4',
        'trigger' => 'MODALIDADEACAOETIQUETA-TRIGGER-4',
        'ativo' => true,
        'criado_em' => '2021-09-16 09:37:58',
        'atualizado_em' => '2021-09-16 09:37:58',
        'apagado_em' => null,
    ];

    protected int $idToDelete = 1;
}
