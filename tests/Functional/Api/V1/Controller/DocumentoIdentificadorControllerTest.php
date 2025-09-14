<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class DocumentoAvulsoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DocumentoIdentificadorControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/documento_identificador';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'DOC-ID-ADMINISTRATIVO-3',
        'codigoDocumento' => 'DOC-ID-ADM3',
        'dataEmissao' => null,
        'emissorDocumento' => 'SECRETARIA 3',
        'modalidadeDocumentoIdentificador' => 1,
        'origemDados' => null,
        'pessoa' => 2,
        'criadoPor' => 4,
    ];

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'nome' => 'DOC-ID-ADMINISTRATIVO-4',
        'codigoDocumento' => 'DOC-ID-ADM4',
        'dataEmissao' => null,
        'emissorDocumento' => 'SECRETARIA 4',
        'modalidadeDocumentoIdentificador' => 2,
        'origemDados' => null,
        'pessoa' => 2,
        'criadoPor' => 4,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'DOC-ID-ADMINISTRATIVO-5',
        'codigoDocumento' => 'DOC-ID-ADM5',
        'dataEmissao' => null,
        'emissorDocumento' => 'SECRETARIA 5',
        'modalidadeDocumentoIdentificador' => 3,
        'origemDados' => null,
        'pessoa' => 2,
        'criadoPor' => 4,
    ];

    protected int $idToDelete = 1;
}
