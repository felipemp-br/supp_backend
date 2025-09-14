<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ApiKeyControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ApiKeyControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000003';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/api_key';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'Api key test',
        'descricao' => 'Api key test',
        'usuario' => 1,
    ];

    protected array $jsonPutBody = [
        'nome' => 'Api key test put ',
        'descricao' => 'Api key test put',
        'usuario' => 2,
        'token' => '8VgSTj9qz8mE9rUbPyDGhhM0NSeykc#A&v05Z%8rd+TU55Uh&nB#C%hU+u&QDAY+',
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'ApiKey Description: test',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
