<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class AcaoControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcaoControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/acao';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'etiqueta' => 7,
        'modalidadeAcaoEtiqueta' => 4,
        'contexto' => 'Ação #POST',
    ];

    protected int $idToDelete = 4;

    protected int $idToPut = 3;

    protected array $jsonPutBody = [
        'etiqueta' => 6,
        'modalidadeAcaoEtiqueta' => 4,
        'contexto' => 'Ação #UPDATE',
    ];

    protected int $idToPatch = 5;

    protected array $jsonPatchBody = [
        'contexto' => 'Ação #PATCH',
    ];
}
