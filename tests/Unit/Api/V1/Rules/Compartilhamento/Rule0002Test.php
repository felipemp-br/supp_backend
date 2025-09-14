<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Compartilhamento/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Compartilhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento as CompartilhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Compartilhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|CompartilhamentoDto $compartilhamentoDto;

    private MockObject|CompartilhamentoEntity $compartilhamentoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->compartilhamentoDto = $this->createMock(CompartilhamentoDto::class);
        $this->compartilhamentoEntity = $this->createMock(CompartilhamentoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testMesmoUsuario(): void
    {
        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->tarefa->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($this->usuario);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->compartilhamentoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::any())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->compartilhamentoDto, $this->compartilhamentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioDiferente(): void
    {
        $usuarioResponsavel = $this->createMock(Usuario::class);
        $usuarioResponsavel->expects(self::any())
            ->method('getId')
            ->willReturn(5);

        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->tarefa->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->tarefa->expects(self::once())
            ->method('getUsuarioResponsavel')
            ->willReturn($usuarioResponsavel);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->compartilhamentoDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->compartilhamentoDto, $this->compartilhamentoEntity, 'transaction')
        );
    }
}
