<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TransicaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TransicaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/transicao';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'observacao' => 'Observação 2',
        'acessoNegado' => false,
        'edital' => 'Edital 2',
        'metodo' => 'Metodo 2',
        'modalidadeTransicao' => 6,
        'processo' => 4,
    ];

    protected array $jsonPutBody = [
        'observacao' => 'Observação 2',
        'acessoNegado' => false,
        'edital' => 'Edital 2',
        'metodo' => 'Metodo 2',
        'modalidadeTransicao' => 6,
        'processo' => 2,
    ];

    protected array $jsonPatchBody = [
        'observacao' => 'Observação 2',
        'acessoNegado' => false,
        'edital' => 'Edital 2',
        'metodo' => 'Metodo 2',
        'modalidadeTransicao' => 6,
        'processo' => 2,
    ];

    private array $searchCriteria = [
        'observacao' => 'Observação 2',
        'acessoNegado' => false,
        'edital' => 'Edital 2',
        'metodo' => 'Metodo 2',
        'modalidadeTransicao' => 6,
        'processo' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
