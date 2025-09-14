<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class TemplateControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TemplateControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->restoreDatabase();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/template';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'ativo' => true,
        'modalidadeTemplate' => 1,
        'documento' => 1,
        'tipoDocumento' => 1,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'ativo' => true,
        'modalidadeTemplate' => 1,
        'documento' => 1,
        'tipoDocumento' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'nome' => 'Nome 2',
        'descricao' => 'Descrição 2',
        'ativo' => true,
        'modalidadeTemplate' => 1,
        'documento' => 1,
        'tipoDocumento' => 1,
    ];

    protected int $idToDelete = 1;
}
