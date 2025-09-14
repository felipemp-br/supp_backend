<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class LotacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LotacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/lotacao';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 5;

    protected int $idToPatch = 3;

    protected array $jsonPostBody = [
        'colaborador' => 3,
        'setor' => 1,
        'peso' => 100,
        'principal' => false,
        'distribuidor' => false,
        'arquivista' => false,
        'pcu' => false,
    ];

    protected array $jsonPutBody = [
        'colaborador' => 1,
        'setor' => 2,
        'peso' => 100,
        'principal' => true,
        'distribuidor' => true,
        'arquivista' => true,
        'pcu' => true,
    ];

    protected array $jsonPatchBody = [
        'pcu' => false,
        'principal' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
