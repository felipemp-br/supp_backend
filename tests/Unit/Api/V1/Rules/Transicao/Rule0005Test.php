<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Transicao/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Transicao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao as TransicaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Transicao\Rule0005;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\ModalidadeTransicao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Transicao as TransicaoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Transicao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|EspecieSetor $especieSetor;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setorAtual;

    private MockObject|TransicaoDto $transicaoDto;

    private MockObject|TransicaoEntity $transicaoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->especieSetor = $this->createMock(EspecieSetor::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorAtual = $this->createMock(Setor::class);
        $this->transicaoDto = $this->createMock(TransicaoDto::class);
        $this->transicaoEntity = $this->createMock(TransicaoEntity::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNUPDeveEstarNoArquivo(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('ARQUIVO', 'DESARQUIVAMENTO');

        $this->especieSetor->expects(self::once())
            ->method('getNome')
            ->willReturn('CORRENTE');

        $this->setorAtual->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $modalidadeTransicao = $this->createMock(ModalidadeTransicao::class);
        $modalidadeTransicao->expects(self::once())
            ->method('getValor')
            ->willReturn('DESARQUIVAMENTO');

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setorAtual);

        $this->transicaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->transicaoDto->expects(self::once())
            ->method('getModalidadeTransicao')
            ->willReturn($modalidadeTransicao);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->transicaoDto, $this->transicaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoDeveEstarNoArquivo(): void
    {
        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ARQUIVO');

        $this->especieSetor->expects(self::once())
            ->method('getNome')
            ->willReturn('ARQUIVO');

        $this->setorAtual->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setorAtual);

        $this->transicaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->transicaoDto, $this->transicaoEntity, 'transaction'));
    }
}
