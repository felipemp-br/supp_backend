<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeVinculacaoProcessoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeVinculacaoProcessoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_vinculacao_processo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'DESANEXAÇÃO',
        'descricao' => 'DESANEXAÇÃO',
        'ativo' => true,
    ];

    protected int $idToPut = 4;

    protected array $jsonPutBody = [
        'valor' => 'DESANEXAÇÃO',
        'descricao' => 'DESANEXAÇÃO',
        'ativo' => true,
    ];

    protected int $idToPatch = 4;

    protected array $jsonPatchBody = [
        'valor' => 'DESANEXAÇÃO',
        'descricao' => 'DESANEXAÇÃO',
        'ativo' => true,
    ];

    protected int $idToDelete = 4;
}
