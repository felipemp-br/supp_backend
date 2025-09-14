<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Colaborador/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Colaborador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Colaborador as ColaboradorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Colaborador\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Colaborador as ColaboradorEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Colaborador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ColaboradorDto $colaboradorDto;

    private MockObject|ColaboradorEntity $colaboradorEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaRepository $tarefaRepository;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->colaboradorDto = $this->createMock(ColaboradorDto::class);
        $this->colaboradorEntity = $this->createMock(ColaboradorEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaRepository = $this->createMock(TarefaRepository::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tarefaRepository
        );
    }

    public function testColaboradorTarefaPendente(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tarefaRepository->expects(self::once())
            ->method('hasAbertaByUsuarioResponsavelId')
            ->willReturn(true);

        $this->colaboradorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->colaboradorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->colaboradorDto, $this->colaboradorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testColaboradorSemTarefaPendente(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tarefaRepository->expects(self::once())
            ->method('hasAbertaByUsuarioResponsavelId')
            ->willReturn(false);

        $this->colaboradorDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->colaboradorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->colaboradorDto, $this->colaboradorEntity, 'transaction'));
    }
}
