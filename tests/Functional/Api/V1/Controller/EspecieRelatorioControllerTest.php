<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieRelatorioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieRelatorioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_relatorio';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 3;

    protected int $idToPatch = 4;

    protected array $jsonPostBody = [
        'generoRelatorio' => 3,
        'nome' => 'ESPÉCIE RELATÓRIO - POST',
        'descricao' => 'ESPÉCIE RELATÓRIO - POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'generoRelatorio' => 2,
        'nome' => 'ESPÉCIE RELATÓRIO - PUT',
        'descricao' => 'ESPÉCIE RELATÓRIO - PUT',
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
