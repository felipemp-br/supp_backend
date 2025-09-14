<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class GeneroDocumentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class GeneroDocumentoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/genero_documento';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected array $jsonPostBody = [
        'nome' => 'ICONOGRÁFICO 1',
        'descricao' => 'ICONOGRÁFICO 1',
        'sigla' => 'ICON1',
        'ativo' => '1',
        'apagadoEm' => null,
        'criadoPor' => '4',
    ];

    protected int $idToPut = 2;

    protected array $jsonPutBody = [
        'nome' => 'ICONOGRÁFICO 2',
        'descricao' => 'ICONOGRÁFICO 2',
        'sigla' => 'ICON2',
    ];

    protected int $idToPatch = 2;

    protected array $jsonPatchBody = [
        'nome' => 'ICONOGRÁFICO 3',
        'descricao' => 'ICONOGRÁFICO 3',
        'sigla' => 'ICON3',
    ];
}
