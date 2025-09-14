<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class StatusBarramentoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoNotificacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/tipo_notificacao';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'nome' => 'CIRCULAR',
        'descricao' => 'CIRCULAR',
    ];

    protected int $idToPut = 6;

    protected array $jsonPutBody = [
        'nome' => 'CIRCULAR',
        'descricao' => 'CIRCULAR',
    ];

    protected int $idToPatch = 6;

    protected array $jsonPatchBody = [
        'nome' => 'CIRCULAR',
        'descricao' => 'CIRCULAR',
    ];

    protected int $idToDelete = 6;
}
