<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0002;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|Processo $processoVinculado;

    private MockObject|ProcessoRepository $processoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->modalidadeVinculacaoProcesso = $this->createMock(ModalidadeVinculacaoProcesso::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->processoRepository = $this->createMock(ProcessoRepository::class);
        $this->processoVinculado = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->processoRepository,
            $this->parameterBag
        );
    }

    public function testNUPTramitacao(): void
    {
        $this->parameterBag->expects(self::atLeast(1))
            ->method('get')
            ->willReturnOnConsecutiveCalls('APENSAMENTO', 'ANEXAÇÃO');

        $this->processoRepository->expects(self::exactly(2))
            ->method('findProcessoEmTramitacao')
            ->willReturn(true);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processoVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('APENSAMENTO');

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

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
    public function testNUPNaoTramitacao(): void
    {
        $this->parameterBag->expects(self::atLeast(1))
            ->method('get')
            ->willReturnOnConsecutiveCalls('APENSAMENTO', 'ANEXAÇÃO');

        $this->processoRepository->expects(self::exactly(2))
            ->method('findProcessoEmTramitacao')
            ->willReturn(false);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processoVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('APENSAMENTO');

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

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
