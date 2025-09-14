<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class VolumeControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VolumeControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/volume';

    protected int $idToGet = 2;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'modalidadeMeio' => 1,
        'processo' => 1,
        'origemDados' => 1,
    ];

    protected array $jsonPutBody = [
        'modalidadeMeio' => 2,
        'processo' => 2,
        'origemDados' => 1,
    ];

    protected array $jsonPatchBody = [
        'modalidadeMeio' => 3,
        'processo' => 1,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
