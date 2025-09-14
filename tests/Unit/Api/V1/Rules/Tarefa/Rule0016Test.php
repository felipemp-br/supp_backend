<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0016Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0016;
use SuppCore\AdministrativoBackend\Entity\Atividade;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0016Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0016Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaEntity $tarefaEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);

        $this->rule = new Rule0016(
            $this->rulesTranslate
        );
    }

    public function testTarefaComAtividades(): void
    {
        $atividade = $this->createMock(Atividade::class);
        $atividades = new ArrayCollection();
        $atividades->add($atividade);

        $this->tarefaEntity->expects(self::once())
            ->method('getAtividades')
            ->willReturn($atividades);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testTarefaSemAtividades(): void
    {
        $this->tarefaEntity->expects(self::once())
            ->method('getAtividades')
            ->willReturn(new ArrayCollection());

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }
}
