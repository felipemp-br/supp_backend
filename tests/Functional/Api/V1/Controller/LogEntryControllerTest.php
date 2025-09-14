<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;
use SuppCore\AdministrativoBackend\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class LogEntryControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LogEntryControllerTest extends WebTestCase
{
    use DatabaseTrait;

    private string $username = '00000000002';

    private string $password = 'Agu123456';

    private string $baseUrl = '/v1/administrativo/logEntry';

    private array $jsonCriteria = [
        'where' => '{"target":"unidade", "id": "1", "entity":"SuppCore\\\AdministrativoBackend\\\Entity\\\Tarefa"}',
        'limit' => 1,
        'offset' => 0,
    ];

    /**
     *
     * @throws Throwable
     */
    public function testThatGetLogRouteReturn200(): void
    {
        $url = $this->baseUrl.'/logentry';
        $response = $this->basicRequest($url, 'GET', $this->username, $this->password, $this->jsonCriteria);

        static::assertInstanceOf(Response::class, $response);
        static::assertJson($response->getContent(), 'A resposta deve ser um JSON');
        static::assertSame(200, $response->getStatusCode());

        $responseJson = json_decode($response->getContent(), true);

        static::assertArrayHasKey('total', $responseJson, 'No JSON de resposta, deve conter a chave "total".');
    }
}
