<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Repositorio/Trigger0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Repositorio;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio as RepositorioDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoRepositorioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio\Trigger0003;
use SuppCore\AdministrativoBackend\Entity\Repositorio as RepositorioEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Repositorio;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003Test extends TestCase
{
    private MockObject|RepositorioDto $repositorioDto;

    private MockObject|RepositorioEntity $repositorioEntity;

    private MockObject|VinculacaoRepositorioResource $vinculacaoRepositorioResource;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositorioDto = $this->createMock(RepositorioDto::class);
        $this->repositorioEntity = $this->createMock(RepositorioEntity::class);
        $this->vinculacaoRepositorioResource = $this->createMock(VinculacaoRepositorioResource::class);

        $this->trigger = new Trigger0003(
            $this->vinculacaoRepositorioResource
        );
    }

    /**
     * @throws Exception
     */
    public function testCriaVinculacaoRepositorio(): void
    {
        $this->vinculacaoRepositorioResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(VinculacaoRepositorio::class), self::isType('string'));

        $this->trigger->execute($this->repositorioDto, $this->repositorioEntity, 'transaction');
    }
}
