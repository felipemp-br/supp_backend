<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AssuntoAdministrativoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssuntoAdministrativoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/assunto_administrativo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'MARKETING',
    ];

    protected int $idToDelete = 2;

    protected int $idToPut = 4;

    protected array $jsonPutBody = [
        'nome' => 'DIREITO TRABALHISTA',
    ];

    protected int $idToPatch = 4;

    protected array $jsonPatchBody = [
        'nome' => 'DIREITO CIVIL',
    ];
}
