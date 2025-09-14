<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Classificacao/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Classificacao as ClassificacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Classificacao\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Classificacao as ClassificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeDestinacao;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Classificacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|ClassificacaoDto $classificacaoDto;

    private MockObject|ClassificacaoEntity $classificacaoEntity;

    private MockObject|ModalidadeDestinacao $modalidadeDestinacao;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classificacaoDto = $this->createMock(ClassificacaoDto::class);
        $this->classificacaoEntity = $this->createMock(ClassificacaoEntity::class);
        $this->modalidadeDestinacao = $this->createMock(ModalidadeDestinacao::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
        );
    }

    public function testModalidadeDestinacaoTransferencia(): void
    {
        $this->modalidadeDestinacao->expects(self::once())
            ->method('getValor')
            ->willReturn('TRANSFERÊNCIA');

        $this->classificacaoDto->expects(self::once())
            ->method('getModalidadeDestinacao')
            ->willReturn($this->modalidadeDestinacao);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->classificacaoDto, $this->classificacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testModalidadeDestinacaoRecolhimento(): void
    {
        $this->modalidadeDestinacao->expects(self::once())
            ->method('getValor')
            ->willReturn('RECOLHIMENTO');

        $this->classificacaoDto->expects(self::once())
            ->method('getModalidadeDestinacao')
            ->willReturn($this->modalidadeDestinacao);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->classificacaoDto, $this->classificacaoEntity, 'transaction'));
    }
}
