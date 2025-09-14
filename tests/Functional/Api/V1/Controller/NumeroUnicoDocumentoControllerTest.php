<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class NumeroUnicoDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroUnicoDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/numero_unico_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'tipoDocumento' => 6,
        'setor' => 2,
        'sequencia' => 1,
    ];

    protected array $jsonPutBody = [
        'tipoDocumento' => 6,
        'setor' => 2,
        'sequencia' => 1,
        'ano' => 2021,
    ];

    protected array $jsonPatchBody = [
        'documento' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
