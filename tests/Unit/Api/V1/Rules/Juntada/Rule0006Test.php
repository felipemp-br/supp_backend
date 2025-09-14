<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Juntada/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Volume;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Juntada;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|JuntadaDto $juntadaDto;

    private MockObject|JuntadaEntity $juntadaEntity;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private MockObject|Volume $volume;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->juntadaDto = $this->createMock(JuntadaDto::class);
        $this->juntadaEntity = $this->createMock(JuntadaEntity::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoRepository = $this->createMock(VinculacaoProcessoRepository::class);
        $this->volume = $this->createMock(Volume::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->vinculacaoProcessoRepository
        );
    }

    public function testNupApensadoAnexado(): void
    {
        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('estaApensada')
            ->willReturn(true);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::once())
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
    public function testNupNaoApensadoAnexado(): void
    {
        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('estaApensada')
            ->willReturn(false);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->volume->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->juntadaDto->expects(self::once())
            ->method('getVolume')
            ->willReturn($this->volume);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->juntadaDto, $this->juntadaEntity, 'transaction'));
    }
}
