<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AssuntoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssuntoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        $this->loadFixtures([
            'testAssunto'
        ]);
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/assunto';

    protected int $idToGet = 2;

    protected array $jsonPostBody = [
        'principal' => true,
        'assuntoAdministrativo' => 1,
        'origemDados' => 1,
        'processo' => 1,
    ];

    protected int $idToDelete = 2;

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'principal' => true,
        'assuntoAdministrativo' => 2,
        'origemDados' => 1,
        'processo' => 1,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'principal' => true,
        'assuntoAdministrativo' => 3,
        'origemDados' => 1,
        'processo' => 1,
    ];
}
