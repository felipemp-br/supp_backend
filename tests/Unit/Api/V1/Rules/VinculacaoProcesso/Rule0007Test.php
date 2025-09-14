<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0007Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0007;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0007Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007Test extends TestCase
{
    private MockObject|ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso;

    private MockObject|OrigemDados $origemDados;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processoVinculado;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->origemDados = $this->createMock(OrigemDados::class);
        $this->processoVinculado = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->modalidadeVinculacaoProcesso = $this->createMock(ModalidadeVinculacaoProcesso::class);

        $this->rule = new Rule0007(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNUPNaoPodeSerVinculado(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('BARRAMENTO_PEN');

        $this->processoVinculado->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->vinculacaoProcessoDto->expects(self::exactly(2))
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('REMISSÃO');

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ANEXAÇÃO');

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPPodeSerVinculado(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('BARRAMENTO_PEN');

        $this->processoVinculado->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->vinculacaoProcessoDto->expects(self::exactly(2))
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('REMISSÃO');

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('REMISSÃO');

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
