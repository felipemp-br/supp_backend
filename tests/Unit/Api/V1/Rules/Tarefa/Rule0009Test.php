<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tarefa/Rule0009Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa\Rule0009;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0009Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tarefa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0009Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TarefaEntity $tarefaEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tarefaEntity = $this->createMock(TarefaEntity::class);

        $this->rule = new Rule0009(
            $this->rulesTranslate
        );
    }

    public function testTarefaConcluida(): void
    {
        $this->tarefaEntity->expects(self::once())
            ->method('getDataHoraConclusaoPrazo')
            ->willReturn(new DateTime());

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->tarefaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testTarefaNaoConcluida(): void
    {
        $this->tarefaEntity->expects(self::once())
            ->method('getDataHoraConclusaoPrazo')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->tarefaEntity, 'transaction'));
    }
}
