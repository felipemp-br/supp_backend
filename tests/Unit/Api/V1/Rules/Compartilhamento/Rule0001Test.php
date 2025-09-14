<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Compartilhamento/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Compartilhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento as CompartilhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Compartilhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|CompartilhamentoDto $compartilhamentoDto;

    private MockObject|CompartilhamentoEntity $compartilhamentoEntity;

    private MockObject|CompartilhamentoRepository $compartilhamentoRepository;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|Usuario $usuario;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->compartilhamentoDto = $this->createMock(CompartilhamentoDto::class);
        $this->compartilhamentoEntity = $this->createMock(CompartilhamentoEntity::class);
        $this->compartilhamentoRepository = $this->createMock(CompartilhamentoRepository::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->usuario = $this->createMock(Usuario::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->compartilhamentoRepository
        );
    }

    public function testUsuarioJaCompartilhadoTarefa(): void
    {
        $this->compartilhamentoRepository->expects(self::any())
            ->method('findByTarefaAndUsuario')
            ->willReturn(true);

        $this->tarefa->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::any())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->compartilhamentoDto, $this->compartilhamentoEntity, 'transaction');
    }

    public function testUsuarioJaCompartilhadoProcesso(): void
    {
        $this->compartilhamentoRepository->expects(self::any())
            ->method('findByProcessoAndUsuario')
            ->willReturn(true);

        $this->processo->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->compartilhamentoDto->expects(self::any())
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
    public function testUsuarioTarefaNaoCompartilhado(): void
    {
        $this->compartilhamentoRepository->expects(self::any())
            ->method('findByTarefaAndUsuario')
            ->willReturn(false);

        $this->tarefa->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getTarefa')
            ->willReturn($this->processo);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->compartilhamentoDto, $this->compartilhamentoEntity, 'transaction')
        );
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioProcessoNaoCompartilhado(): void
    {
        $this->compartilhamentoRepository->expects(self::any())
            ->method('findByProcessoAndUsuario')
            ->willReturn(false);

        $this->processo->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->usuario->expects(self::any())
            ->method('getId')
            ->willReturn(1);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->compartilhamentoDto->expects(self::any())
            ->method('getTarefa')
            ->willReturn($this->processo);

        $this->compartilhamentoDto->expects(self::any())
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
