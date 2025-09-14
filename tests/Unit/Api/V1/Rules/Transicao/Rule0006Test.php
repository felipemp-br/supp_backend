<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Transicao/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Transicao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao as TransicaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTransicao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Transicao as TransicaoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Transicao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|Classificacao $classificacao;

    private MockObject|ModalidadeFase $modalidadeFase;

    private MockObject|ModalidadeTransicao $modalidadeTransicao;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TransicaoDto $transicaoDto;

    private MockObject|TransicaoEntity $transicaoEntity;

    private ParameterBagInterface $parameterBag;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->classificacao = $this->createMock(Classificacao::class);
        $this->modalidadeFase = $this->createMock(ModalidadeFase::class);
        $this->modalidadeTransicao = $this->createMock(ModalidadeTransicao::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->transicaoDto = $this->createMock(TransicaoDto::class);
        $this->transicaoEntity = $this->createMock(TransicaoEntity::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNUPFaseCorrenteNaoPodeSerEliminado(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('ELIMINAÇÃO', 'CORRENTE');

        $this->modalidadeTransicao->expects(self::once())
            ->method('getValor')
            ->willReturn('ELIMINAÇÃO');

        $this->modalidadeFase->expects(self::once())
            ->method('getValor')
            ->willReturn('CORRENTE');

        $this->classificacao->expects(self::once())
            ->method('getPrazoGuardaFaseIntermediariaEvento')
            ->willReturn('90');

        $this->processo->expects(self::any())
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->processo->expects(self::once())
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->transicaoDto->expects(self::once())
            ->method('getModalidadeTransicao')
            ->willReturn($this->modalidadeTransicao);

        $this->transicaoDto->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->transicaoDto, $this->transicaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPFaseCorrentePodeSerEliminado(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('ELIMINAÇÃO', 'CORRENTE');

        $this->modalidadeTransicao->expects(self::once())
            ->method('getValor')
            ->willReturn('ELIMINAÇÃO');

        $this->modalidadeFase->expects(self::once())
            ->method('getValor')
            ->willReturn('CORRENTE');

        $this->classificacao->expects(self::once())
            ->method('getPrazoGuardaFaseIntermediariaAno')
            ->willReturn(null);

        $this->classificacao->expects(self::once())
            ->method('getPrazoGuardaFaseIntermediariaMes')
            ->willReturn(null);

        $this->classificacao->expects(self::once())
            ->method('getPrazoGuardaFaseIntermediariaDia')
            ->willReturn(null);

        $this->classificacao->expects(self::once())
            ->method('getPrazoGuardaFaseIntermediariaEvento')
            ->willReturn(null);

        $this->processo->expects(self::any())
            ->method('getClassificacao')
            ->willReturn($this->classificacao);

        $this->processo->expects(self::once())
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->transicaoDto->expects(self::once())
            ->method('getModalidadeTransicao')
            ->willReturn($this->modalidadeTransicao);

        $this->transicaoDto->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->transicaoDto, $this->transicaoEntity, 'transaction'));
    }
}
