<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Tests\Functional\Api\V1\Controller;

use SuppCore\AdministrativoBackend\Utils\Tests\DatabaseTrait;

/**
 * Class SigiloControllerTest.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SigiloControllerTest extends ControllerTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->restoreDatabase();
        parent::setUp();
    }

    protected static string $username = '00000000004';

    protected static string $password = 'Agu123456';

    protected string $baseUrl = '/v1/administrativo/sigilo';

    protected int $idToGet = 1;

    protected array $jsonPostBody = [
        'observacao' => 'SIGILO-2',
        'fundamentoLegal' => 'Fundamento Legal 2',
        'nivelAcesso' => 1,
        'razoesClassificacaoSigilo' => null,
        'tipoSigilo' => 1,
        'origemDados' => 1,
        'processo' => 1,
        'documento' => null,
        'modalidadeCategoriaSigilo' => null,
        'codigoIndexacao' => null,
        'desclassificado' => false,
        'dataHoraInicioSigilo' => '2021-09-27 18:00:00',
        'dataHoraValidadeSigilo' => '2020-10-27 18:00:00',
    ];

    protected int $idToPut = 1;

    protected array $jsonPutBody = [
        'observacao' => 'SIGILO-2',
        'fundamentoLegal' => 'Fundamento Legal 2',
        'nivelAcesso' => 1,
        'razoesClassificacaoSigilo' => null,
        'tipoSigilo' => 1,
        'origemDados' => 1,
        'processo' => 1,
        'documento' => null,
        'modalidadeCategoriaSigilo' => null,
        'codigoIndexacao' => null,
        'desclassificado' => false,
        'dataHoraInicioSigilo' => '2021-09-27 18:00:00',
        'dataHoraValidadeSigilo' => '2020-10-27 18:00:00',
    ];

    protected int $idToPatch = 1;

    protected array $jsonPatchBody = [
        'observacao' => 'SIGILO-2',
        'fundamentoLegal' => 'Fundamento Legal 2',
        'nivelAcesso' => 1,
        'razoesClassificacaoSigilo' => null,
        'tipoSigilo' => 1,
        'origemDados' => 1,
        'processo' => 1,
        'documento' => null,
        'modalidadeCategoriaSigilo' => null,
        'codigoIndexacao' => null,
        'desclassificado' => false,
        'dataHoraInicioSigilo' => '2021-09-27 18:00:00',
        'dataHoraValidadeSigilo' => '2020-10-27 18:00:00',
    ];

    protected int $idToDelete = 1;
}
