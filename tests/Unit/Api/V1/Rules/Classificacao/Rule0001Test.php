<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Classificacao/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Classificacao\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ClassificacaoDto $classificacaoDto;

    private MockObject|ClassificacaoEntity $classificacaoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classificacaoDto = $this->createMock(ClassificacaoDto::class);
        $this->classificacaoEntity = $this->createMock(ClassificacaoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
        );
    }

    public function testExcedeNivelMaximoDaArvore(): void
    {
        $parent = $this->createMock(ClassificacaoEntity::class);
        $parent->expects(self::once())
            ->method('getLvl')
            ->willReturn(20);

        $this->classificacaoDto->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn($parent);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->classificacaoDto, $this->classificacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNaoExcedeNivelMaximoDaArvore(): void
    {
        $parent = $this->createMock(ClassificacaoEntity::class);
        $parent->expects(self::once())
            ->method('getLvl')
            ->willReturn(9);

        $this->classificacaoDto->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn($parent);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->classificacaoDto, $this->classificacaoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testSemClassificacaoPai(): void
    {
        $this->classificacaoDto->expects(self::once())
            ->method('getParent')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->classificacaoDto, $this->classificacaoEntity, 'transaction'));
    }
}
