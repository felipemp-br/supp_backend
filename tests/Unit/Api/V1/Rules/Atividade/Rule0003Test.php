<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Atividade/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Atividade;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Atividade\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\EspecieTarefa;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\VinculacaoWorkflow;
use SuppCore\AdministrativoBackend\Entity\Workflow;
use SuppCore\AdministrativoBackend\Repository\TransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Atividade;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AtividadeDto $atividadeDto;

    private MockObject|AtividadeEntity $atividadeEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Tarefa $tarefa;

    private MockObject|TransicaoWorkflowRepository $transicaoWorkflowRepository;

    private MockObject|VinculacaoWorkflow $vinculacaoWorkflow;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->atividadeDto = $this->createMock(AtividadeDto::class);
        $this->atividadeEntity = $this->createMock(AtividadeEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefa = $this->createMock(Tarefa::class);
        $this->transicaoWorkflowRepository = $this->createMock(TransicaoWorkflowRepository::class);
        $this->vinculacaoWorkflow = $this->createMock(VinculacaoWorkflow::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->transicaoWorkflowRepository
        );
    }

    /**
     * @throws RuleException
     */
    public function testTarefaNaoEncerrada(): void
    {
        $this->tarefa->expects(self::once())
            ->method('getVinculacaoWorkflow')
            ->willReturn($this->vinculacaoWorkflow);

        $this->atividadeDto->expects(self::once())
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testTransicaoPermitida(): void
    {
        $tarefaAtual = $this->createMock(Tarefa::class);
        $tarefaAtual->expects(self::once())
            ->method('getEspecieTarefa')
            ->willReturn($this->createMock(EspecieTarefa::class));

        $this->vinculacaoWorkflow->expects(self::once())
            ->method('getTarefaAtual')
            ->willReturn($tarefaAtual);

        $workflow = $this->createMock(Workflow::class);

        $this->vinculacaoWorkflow->expects(self::exactly(2))
            ->method('getWorkflow')
            ->willReturn($workflow);

        $this->tarefa->expects(self::exactly(4))
            ->method('getVinculacaoWorkflow')
            ->willReturn($this->vinculacaoWorkflow);

        $this->atividadeDto->expects(self::exactly(5))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $this->transicaoWorkflowRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn([[]]);

        $this->transicaoWorkflowRepository->expects(self::once())
            ->method('findBy')
            ->willReturn([[]]);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction'));
    }

    public function testTransicaoNaoPermitida(): void
    {
        $tarefaAtual = $this->createMock(Tarefa::class);
        $tarefaAtual->expects(self::once())
            ->method('getEspecieTarefa')
            ->willReturn($this->createMock(EspecieTarefa::class));

        $this->vinculacaoWorkflow->expects(self::once())
            ->method('getTarefaAtual')
            ->willReturn($tarefaAtual);

        $workflow = $this->createMock(Workflow::class);

        $this->vinculacaoWorkflow->expects(self::exactly(2))
            ->method('getWorkflow')
            ->willReturn($workflow);

        $this->tarefa->expects(self::exactly(4))
            ->method('getVinculacaoWorkflow')
            ->willReturn($this->vinculacaoWorkflow);

        $this->atividadeDto->expects(self::exactly(5))
            ->method('getTarefa')
            ->willReturn($this->tarefa);

        $this->atividadeDto->expects(self::once())
            ->method('getEncerraTarefa')
            ->willReturn(true);

        $this->transicaoWorkflowRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn([[]]);

        $this->transicaoWorkflowRepository->expects(self::once())
            ->method('findBy')
            ->willReturn([]);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->atividadeDto, $this->atividadeEntity, 'transaction');
    }
}
