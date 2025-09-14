<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Juntada/Rule0008Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada\Rule0008;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0008Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008Test extends TestCase
{
    private MockObject|Documento $documento;

    private MockObject|JuntadaDto $juntadaDto;

    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|ModalidadeMeio $modalidadeMeio;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Volume $volume;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->documento = $this->createMock(Documento::class);
        $this->juntadaDto = $this->createMock(JuntadaDto::class);
        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->modalidadeMeio = $this->createMock(ModalidadeMeio::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0008(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNupEletronico(): void
    {
        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->documento->expects(self::once())
            ->method('getComponentesDigitais')
            ->willReturn(new ArrayCollection());

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::once())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->juntadaDto->expects(self::exactly(2))
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNupFisico(): void
    {
        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('FÍSICO');

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->documento->expects(self::once())
            ->method('getComponentesDigitais')
            ->willReturn(new ArrayCollection());

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::once())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->juntadaDto->expects(self::exactly(2))
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction'));
    }
}
