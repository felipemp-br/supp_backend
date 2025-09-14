<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/RelacionamentoPessoal/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\RelacionamentoPessoal;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\RelacionamentoPessoal\Rule0002;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\RelacionamentoPessoal as RelacionamentoPessoalEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\RelacionamentoPessoal;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|RelacionamentoPessoalEntity $relacionamentoPessoalEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->relacionamentoPessoalEntity = $this->createMock(RelacionamentoPessoalEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testRelacionamentoComIntegracao(): void
    {
        $origemDados = $this->createMock(OrigemDados::class);
        $this->relacionamentoPessoalEntity->expects(self::once())
            ->method('getOrigemDados')
            ->willReturn($origemDados);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->relacionamentoPessoalEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRelacionamentoSemIntegracao(): void
    {
        $this->relacionamentoPessoalEntity->expects(self::once())
            ->method('getOrigemDados')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->relacionamentoPessoalEntity, 'transaction'));
    }
}
