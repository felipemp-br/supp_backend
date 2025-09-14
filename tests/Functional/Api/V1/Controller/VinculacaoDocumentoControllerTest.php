<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VinculacaoDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/vinculacao_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'documento' => 2,
        'documentoVinculado' => 3,
        'modalidadeVinculacaoDocumento' => 1,
    ];

    protected array $jsonPutBody = [
        'documento' => 3,
        'documentoVinculado' => 2,
        'modalidadeVinculacaoDocumento' => 1,
    ];

    protected array $jsonPatchBody = [
        'modalidadeVinculacaoDocumento' => 2,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
