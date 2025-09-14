<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModuloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModuloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modulo';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'JUDICIAL',
        'descricao' => 'MÓDULO JUDICIAL',
        'sigla' => 'JUD',
        'prefixo' => 'supp_core.judicial_backend',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'nome' => 'DíVIDA',
        'descricao' => 'MÓDULO DíVIDA',
        'sigla' => 'DIV',
        'prefixo' => 'supp_core.divida_backend',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'sigla' => 'ABC',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
