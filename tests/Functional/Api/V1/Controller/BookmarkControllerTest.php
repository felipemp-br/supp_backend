<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class BookmarkControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class BookmarkControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected static string $username = '00000000002';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/bookmark';

    protected int $idToGet = 1;

    protected int $idToDelete = 1;

    protected int $idToPut = 1;

    protected int $idToPatch = 1;

    protected array $jsonPostBody = [
        'nome' => 'Bookmark test',
        'descricao' => 'Bookmark test',
        'pagina' => 2,
        'processo' => 1,
        'componenteDigital' => 1,
        'juntada' => 1,
        'usuario' => 3,
        'corHexadecimal' => '#F0FFFF',
    ];

    protected array $jsonPutBody = [
        'nome' => 'Bookmark test',
        'descricao' => 'Bookmark test',
        'pagina' => 10,
        'processo' => 2,
        'componenteDigital' => 2,
        'juntada' => 2,
        'usuario' => 5,
        'corHexadecimal' => '#FFFFFF',
    ];

    protected array $jsonPatchBody = [
        'pagina' => 5,
        'processo' => 3,
    ];

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }
}
