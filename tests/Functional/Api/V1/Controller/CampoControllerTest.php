<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class CampoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CampoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/campo';

    protected int $idToGet = 2;

    protected int $idToDelete = 4;

    protected int $idToPut = 2;

    protected int $idToPatch = 3;

    protected array $jsonPostBody = [
        'nome' => 'CAMPO POST',
        'descricao' => 'DESCRIÇÃO CAMPO POST',
        'html' => '<span data-method="" data-options="" data-service="">*campo post teste*</span>',
    ];

    protected array $jsonPutBody = [
        'nome' => 'CAMPO PUT',
        'descricao' => 'DESCRIÇÃO CAMPO PUT',
        'html' => '<span data-method="" data-options="" data-service="">*campo put teste*</span>',
    ];

    protected array $jsonPatchBody = [
        'descricao' => 'DESCRIÇÃO CAMPO PATCH',
        'html' => '<span data-method="" data-options="" data-service="">*campo patch teste*</span>',
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
