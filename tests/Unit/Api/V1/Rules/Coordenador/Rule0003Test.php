<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Coordenador/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Coordenador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador as CoordenadorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Coordenador\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Coordenador as CoordenadorEntity;
use SuppCore\AdministrativoBackend\Entity\Coordenador;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\CoordenadorRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Coordenador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|CoordenadorDto $coordenadorDto;

    private MockObject|CoordenadorEntity $coordenadorEntity;

    private MockObject|CoordenadorRepository $coordenadorRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coordenadorDto = $this->createMock(CoordenadorDto::class);
        $this->coordenadorEntity = $this->createMock(CoordenadorEntity::class);
        $this->coordenadorRepository = $this->createMock(CoordenadorRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->coordenadorRepository
        );
    }

    public function testTemCoordenadorTipoSetor(): void
    {
        $setor = $this->createMock(Setor::class);
        $setor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndSetor')
            ->willReturn($this->createMock(CoordenadorEntity::class));

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNaoTemCoordenadorTipoSetor(): void
    {
        $setor = $this->createMock(Setor::class);
        $setor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndSetor')
            ->willReturn(false);

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction'));
    }

    public function testTemCoordenadorTipoUnidade(): void
    {
        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndUnidade')
            ->willReturn($this->createMock(CoordenadorEntity::class));

        $this->coordenadorDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNaoTemCoordenadorTipoUnidade(): void
    {
        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndUnidade')
            ->willReturn(false);

        $this->coordenadorDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction'));
    }

    public function testTemCoordenadorOrgaoCentral(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);
        $modalidadeOrgaoCentral->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndOrgaoCentral')
            ->willReturn($this->createMock(Coordenador::class));

        $this->coordenadorDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNaoTemCoordenadorOrgaoCentral(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);
        $modalidadeOrgaoCentral->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->coordenadorRepository->expects(self::once())
            ->method('findCoordenadorByUsuarioAndOrgaoCentral')
            ->willReturn(false);

        $this->coordenadorDto->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorDto->expects(self::exactly(2))
            ->method('getOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->coordenadorDto, $this->coordenadorEntity, 'transaction'));
    }
}
