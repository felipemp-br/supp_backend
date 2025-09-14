<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Juntada/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada\Rule0003;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeFase;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|EspecieSetor $especieSetor;

    private MockObject|JuntadaDto $juntadaDto;

    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|ModalidadeFase $modalidadeFase;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setor;

    private MockObject|Volume $volume;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->especieSetor = $this->createMock(EspecieSetor::class);
        $this->juntadaDto = $this->createMock(JuntadaDto::class);
        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->modalidadeFase = $this->createMock(ModalidadeFase::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setor = $this->createMock(Setor::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNUPEncerrado(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('ARQUIVO', 'INTERMEDIÁRIA', 'DEFINITIVA', 'ELIMINADO');

        $this->especieSetor->expects(self::once())
            ->method('getNome')
            ->willReturn('PROTOCOLO');

        $this->setor->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $this->modalidadeFase->expects(self::exactly(3))
            ->method('getValor')
            ->willReturn('ELIMINADO');

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setor);

        $this->volume->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::any())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoEncerrado(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('ARQUIVO', 'INTERMEDIÁRIA', 'DEFINITIVA', 'ELIMINADO');

        $this->especieSetor->expects(self::once())
            ->method('getNome')
            ->willReturn('PROTOCOLO');

        $this->setor->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($this->especieSetor);

        $this->modalidadeFase->expects(self::exactly(3))
            ->method('getValor')
            ->willReturn('CORRENTE');

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getModalidadeFase')
            ->willReturn($this->modalidadeFase);

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setor);

        $this->volume->expects(self::any())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::any())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction'));
    }
}
