<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class ServidorEmailControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ServidorEmailControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/servidor_email';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'descricao' => 'Descrição 2',
        'nome' => 'Nome 2',
        'host' => 'Host 2',
        'porta' => 587,
        'protocolo' => 'IMAP',
        'metodoEncriptacao' => 'SMTP STARTTLS ',
        'ativo' => false,
        'validaCertificado' => false,
        'criadoEm' => '2021-10-01 13:00:00',
        'criadoPor' => 4,

    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'descricao' => 'Descrição 2',
        'nome' => 'Nome 2',
        'host' => 'Host 2',
        'porta' => 587,
        'protocolo' => 'IMAP',
        'metodoEncriptacao' => 'SMTP STARTTLS ',
        'ativo' => false,
        'validaCertificado' => false,
        'criadoEm' => '2021-10-01 13:00:00',
        'criadoPor' => 4,
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'descricao' => 'Descrição 2',
        'nome' => 'Nome 2',
        'host' => 'Host 2',
        'porta' => 587,
        'protocolo' => 'IMAP',
        'metodoEncriptacao' => 'SMTP STARTTLS ',
        'ativo' => false,
        'validaCertificado' => false,
        'criadoEm' => '2021-10-01 13:00:00',
        'criadoPor' => 4,
    ];

    protected int $idToDelete = 1;
}
