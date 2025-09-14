<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class RelevanciaControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RelevanciaControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/relevancia';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'observacao' => 'OBSERVACAO-3',
        'especieRelevancia' => 2,
        'processo' => 1,
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'observacao' => 'OBSERVACAO-3',
        'especieRelevancia' => 1,
        'processo' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'observacao' => 'OBSERVACAO-3',
        'especieRelevancia' => 1,
        'processo' => 1,
    ];

    protected int $idToDelete = 1;
}
