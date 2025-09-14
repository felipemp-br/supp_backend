<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class MunicipioControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MunicipioControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/municipio';

    protected int $idToGet = 1;

    protected int $idToDelete = 75;

    protected int $idToPut = 25;

    protected int $idToPatch = 50;

    protected array $jsonPostBody = [
        'nome' => 'BOTUJURU',
        'estado' => 26,
        'codigoIBGE' => '7654321',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'ECOPORANGA',
        'estado' => 8,
        'codigoIBGE' => '1234567',
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
