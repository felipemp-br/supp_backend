<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0003;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|ModalidadeMeio $modalidadeMeio;

    private MockObject|ModalidadeMeio $modalidadeMeioProcessoVinculado;

    private MockObject|ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|Processo $processoVinculado;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->modalidadeMeio = $this->createMock(ModalidadeMeio::class);
        $this->modalidadeMeioProcessoVinculado = $this->createMock(ModalidadeMeio::class);
        $this->modalidadeVinculacaoProcesso = $this->createMock(ModalidadeVinculacaoProcesso::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->processoVinculado = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testMeioDiferente(): void
    {
        $this->parameterBag->expects(self::atLeast(1))
            ->method('get')
            ->willReturnOnConsecutiveCalls('APENSAMENTO', 'ANEXAÇÃO');

        $this->modalidadeVinculacaoProcesso->expects(self::atLeast(1))
            ->method('getValor')
            ->willReturn('ANEXAÇÃO');

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->modalidadeMeioProcessoVinculado->expects(self::once())
            ->method('getValor')
            ->willReturn('FÍSICO');

        $this->processoVinculado->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioProcessoVinculado);

        $this->vinculacaoProcessoDto->expects(self::atLeast(1))
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testMeioIgual(): void
    {
        $this->parameterBag->expects(self::atLeast(1))
            ->method('get')
            ->willReturnOnConsecutiveCalls('APENSAMENTO', 'ANEXAÇÃO');

        $this->modalidadeVinculacaoProcesso->expects(self::atLeast(1))
            ->method('getValor')
            ->willReturn('ANEXAÇÃO');

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->modalidadeMeioProcessoVinculado->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->processoVinculado->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioProcessoVinculado);

        $this->vinculacaoProcessoDto->expects(self::atLeast(1))
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
