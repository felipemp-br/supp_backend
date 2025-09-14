<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lotacao/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|LotacaoDto $lotacaoDto;

    private MockObject|LotacaoEntity $lotacaoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setor;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->lotacaoDto = $this->createMock(LotacaoDto::class);
        $this->lotacaoEntity = $this->createMock(LotacaoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setor = $this->createMock(Setor::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testLotacaoInativa(): void
    {
        $this->setor->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->lotacaoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->lotacaoDto, $this->lotacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testLotacaoAtiva(): void
    {
        $this->setor->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->lotacaoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->lotacaoDto, $this->lotacaoEntity, 'transaction'));
    }
}
