<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0002;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\GeneroTarefa;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|EspecieTarefa $especieTarefa;

    private MockObject|GeneroTarefa $generoTarefa;

    private MockObject|ModalidadeFase $modalidadeFase;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaDto $tarefaDto;

    private MockObject|TarefaEntity $tarefaEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->especieTarefa = $this->createMock(EspecieTarefa::class);
        $this->generoTarefa = $this->createMock(GeneroTarefa::class);
        $this->modalidadeFase = $this->createMock(ModalidadeFase::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaDto = $this->createMock(TarefaDto::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->parameterBag,
        );
    }

    public function testNUPArquivado(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('CORRENTE', 'ARQUIVÍSTICO');

        $this->generoTarefa->expects(self::once())
            ->method('getNome')
            ->willReturn('ADMINISTRATIVO');

        $this->especieTarefa->expects(self::once())
            ->method('getGeneroTarefa')
            ->willReturn($this->generoTarefa);

        $this->modalidadeFase->expects(self::once())
            ->method('getValor')
            ->willReturn('INTERMEDIÁRIA');

        $this->processo->expects(self::once())
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->tarefaDto->expects(self::once())
            ->method('getEspecieTarefa')
            ->willReturn($this->especieTarefa);

        $this->tarefaDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoArquivado(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('CORRENTE', 'ARQUIVÍSTICO');

        $this->generoTarefa->expects(self::once())
            ->method('getNome')
            ->willReturn('ARQUIVÍSTICO');

        $this->especieTarefa->expects(self::once())
            ->method('getGeneroTarefa')
            ->willReturn($this->generoTarefa);

        $this->modalidadeFase->expects(self::once())
            ->method('getValor')
            ->willReturn('INTERMEDIÁRIA');

        $this->processo->expects(self::once())
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->tarefaDto->expects(self::once())
            ->method('getEspecieTarefa')
            ->willReturn($this->especieTarefa);

        $this->tarefaDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tarefaDto, $this->tarefaEntity, 'transaction'));
    }
}
