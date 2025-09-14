<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Pessoa/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Pessoa;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Pessoa\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Pessoa;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|PessoaEntity $pessoaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->pessoaEntity = $this->createMock(PessoaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate
        );
    }

    public function testPessoaConveniada(): void
    {
        $this->pessoaEntity->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->pessoaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testPessoaNaoConveniada(): void
    {
        $this->pessoaEntity->expects(self::any())
            ->method('getPessoaValidada')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->pessoaEntity, 'transaction');
    }
}
