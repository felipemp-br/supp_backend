<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeOrgaoCentralControllerTest.
 *
 * @author Willlian Santos <willian.santos@agu.gov.br>
 */
class ModalidadeOrgaoCentralControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_orgao_central';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'valor' => 'Teste POST',
        'descricao' => 'Descrição de Modalidade de Teste POST',
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'valor' => 'Teste PUT',
        'descricao' => 'Descrição de Modalidade de Teste PUT',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'valor' => 'Teste PATCH',
        'descricao' => 'Descrição de Modalidade',
    ];

    protected int $idToDelete = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }
}
