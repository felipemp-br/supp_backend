<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Volume/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Volume;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume as VolumeDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Volume\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume as VolumeEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Volume;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ModalidadeMeio $modalidadeMeio;

    private MockObject|ModalidadeMeio $modalidadeMeioProcesso;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VolumeDto $volumeDto;

    private MockObject|VolumeEntity$volumeEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->modalidadeMeio = $this->createMock(ModalidadeMeio::class);
        $this->modalidadeMeioProcesso = $this->createMock(ModalidadeMeio::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->volumeDto = $this->createMock(VolumeDto::class);
        $this->volumeEntity = $this->createMock(VolumeEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testNaoEletronico(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeioProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioProcesso);

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('HÍBRIDO');

        $this->volumeDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->volumeDto->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->volumeDto, $this->volumeEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testEletronico(): void
    {
        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturn('ELETRÔNICO');

        $this->modalidadeMeioProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->processo->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeioProcesso);

        $this->modalidadeMeio->expects(self::once())
            ->method('getValor')
            ->willReturn('ELETRÔNICO');

        $this->volumeDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->volumeDto->expects(self::once())
            ->method('getModalidadeMeio')
            ->willReturn($this->modalidadeMeio);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->volumeDto, $this->volumeEntity, 'transaction');
    }
}
