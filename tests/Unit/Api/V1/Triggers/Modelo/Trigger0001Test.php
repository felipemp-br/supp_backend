<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Modelo/Trigger0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Modelo;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo as ModeloDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoModeloResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Trigger0001;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Modelo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ModeloDto $modeloDto;

    private MockObject|ModeloEntity $modeloEntity;

    private MockObject|VinculacaoModeloResource $vinculacaoModeloResource;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->modeloDto = $this->createMock(ModeloDto::class);
        $this->modeloEntity = $this->createMock(ModeloEntity::class);
        $this->vinculacaoModeloResource = $this->createMock(VinculacaoModeloResource::class);

        $this->trigger = new Trigger0001(
            $this->vinculacaoModeloResource,
            $this->authorizationChecker
        );
    }

    /**
     * @throws Exception
     */
    public function testCriaVinculacaoRepositorio(): void
    {
        $this->authorizationChecker->expects(self::never())
            ->method('isGranted')
            ->willReturn(false);

        $this->modeloDto->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($this->createMock(Setor::class));

        $this->modeloDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->createMock(Usuario::class));

        $this->modeloDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn($this->createMock(Setor::class));

        $this->modeloDto->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $this->vinculacaoModeloResource->expects(self::once())
            ->method('create')
            ->with(self::isInstanceOf(VinculacaoModelo::class), self::isType('string'));

        $this->trigger->execute($this->modeloDto, $this->modeloEntity, 'transaction');
    }

    /**
     * @throws Exception
     */
    public function testNaoCriaVinculacaoRepositorio(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->modeloDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->modeloDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn(null);

        $this->modeloDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->modeloDto->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $this->vinculacaoModeloResource->expects(self::never())
            ->method('create')
            ->with(self::isInstanceOf(VinculacaoModelo::class), self::isType('string'));

        $this->trigger->execute($this->modeloDto, $this->modeloEntity, 'transaction');
    }
}
