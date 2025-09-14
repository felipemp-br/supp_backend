<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TipoDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 3;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'especieDocumento' => 2,
        'nome' => 'CONTRATO',
        'descricao' => 'CONTRATO',
        'sigla' => 'CONTR',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'especieDocumento' => 1,
        'nome' => 'PROCURAÇÃO',
        'descricao' => 'PROCURAÇÃO',
        'sigla' => 'PROC',
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
