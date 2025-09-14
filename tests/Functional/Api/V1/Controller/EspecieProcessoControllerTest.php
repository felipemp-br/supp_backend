<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieProcessoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieProcessoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_processo';

    protected int $idToGet = 2;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'generoProcesso' => 1,
        'classificacao' => 5,
        'nome' => 'ESPÉCIE PROCESSO - POST',
        'descricao' => 'ESPÉCIE PROCESSO - POST',
        'modalidadeMeio' => 1,
        'titulo' => 'TESTE',
    ];

    protected array $jsonPutBody = [
        'generoProcesso' => 1,
        'classificacao' => 2,
        'nome' => 'ESPÉCIE PROCESSO - PUT',
        'descricao' => 'ESPÉCIE PROCESSO - PUT',
        'modalidadeMeio' => 2,
        'titulo' => 'TESTE',
    ];

    protected array $jsonPatchBody = [
        'nome' => 'ESPÉCIE PROCESSO - PATCH',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
