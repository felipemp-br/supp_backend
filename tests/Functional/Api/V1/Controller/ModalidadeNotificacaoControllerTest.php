<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ModalidadeNotificacaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ModalidadeNotificacaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/modalidade_notificacao';

    protected int $idToGet = 1;

    protected int $idToDelete = 2;

    protected int $idToPut = 2;

    protected int $idToPatch = 2;

    protected array $jsonPostBody = [
        'valor' => 'TESTE MODALIDADE NOTIFICAÇÃO - POST',
        'descricao' => 'TESTE MODALIDADE NOTIFICAÇÃO- POST',
        'ativo' => true,
    ];

    protected array $jsonPutBody = [
        'valor' => 'TESTE MODALIDADE NOTIFICAÇÃO - PUT',
        'descricao' => 'TESTE MODALIDADE NOTIFICAÇÃO - PUT',
        'ativo' => true,
    ];

    protected array $jsonPatchBody = [
        'ativo' => false,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
