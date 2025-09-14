<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class LocalizadorControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LocalizadorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/localizador';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 1;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'setor' => 2,
        'nome' => 'TESTE LOCALIZADOR',
        'descricao' => 'TESTE LOCALIZADOR',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'setor' => 1,
        'nome' => 'TESTE LOCALIZADOR',
        'descricao' => 'TESTE LOCALIZADOR',
    ];

    protected array $jsonPatchBody = [
        'ativo' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testLocalizador',
        ]);
        parent::setUp();
    }
}
