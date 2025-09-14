<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Juntada/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|ComponenteDigitalRepository $componenteDigitalRepository;

    private MockObject|Documento $documento;

    private MockObject|JuntadaDto $juntadaDto;

    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|OrigemDados $origemDados;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Volume $volume;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->componenteDigitalRepository = $this->createMock(ComponenteDigitalRepository::class);
        $this->documento = $this->createMock(Documento::class);
        $this->juntadaDto = $this->createMock(JuntadaDto::class);
        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->origemDados = $this->createMock(OrigemDados::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->componenteDigitalRepository
        );
    }

    public function testNUPIntegracao(): void
    {
        $this->componenteDigitalRepository->expects(self::once())
            ->method('findCountByDocumento')
            ->willReturn(2);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('BARRAMENTO_PEN');

        $this->processo->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->volume->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::exactly(2))
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->juntadaDto->expects(self::once())
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
    public function testNUPNaoIntegracao(): void
    {
        $this->componenteDigitalRepository->expects(self::once())
            ->method('findCountByDocumento')
            ->willReturn(0);

        $this->documento->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('BARRAMENTO_PEN');

        $this->processo->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->volume->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::exactly(2))
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->juntadaDto->expects(self::once())
            ->method('getDocumento')
            ->willReturn($this->documento);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction'));
    }
}
