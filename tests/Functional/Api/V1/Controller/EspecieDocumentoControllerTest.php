<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class EspecieDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/especie_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected array $jsonPostBody = [
        'nome' => 'ADMINISTRATIVO 1',
        'descricao' => 'ADMINISTRATIVO 1',
        'sigla' => 'ADM1',
        'ativo' => '1',
        'generoDocumento' => '1',
        'criadoPor' => '4',
    ];

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'nome' => 'ADMINISTRATIVO 2',
        'descricao' => 'ADMINISTRATIVO 2',
        'sigla' => 'ADM2',
        'ativo' => '1',
        'generoDocumento' => '2',
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'nome' => 'ADMINISTRATIVO 3',
        'descricao' => 'ADMINISTRATIVO 3',
        'sigla' => 'ADM3',
        'ativo' => '1',
        'generoDocumento' => '3',
    ];
}
