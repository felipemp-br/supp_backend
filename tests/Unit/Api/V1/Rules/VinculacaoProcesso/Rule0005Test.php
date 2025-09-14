<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0005;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|Processo $processo;

    private MockObject|Processo $processoVinculado;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->processo = $this->createMock(Processo::class);
        $this->processoVinculado = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate
        );
    }

    public function testVinculacaoMesmoProcessoId(): void
    {
        $this->processoVinculado->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoProcessoDto->expects(self::exactly(2))
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->vinculacaoProcessoDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    public function testVinculacaoMesmoProcessoUuid(): void
    {
        $this->processoVinculado->expects(self::exactly(2))
            ->method('getUuid')
            ->willReturn('baa31c57-e5da-49a2-842d-f547437061f2');

        $this->processo->expects(self::exactly(2))
            ->method('getUuid')
            ->willReturn('baa31c57-e5da-49a2-842d-f547437061f2');

        $this->vinculacaoProcessoDto->expects(self::exactly(2))
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->vinculacaoProcessoDto->expects(self::exactly(3))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testVinculacaoProcessoDiferente(): void
    {
        $this->processoVinculado->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(5);

        $this->processo->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(10);

        $this->processoVinculado->expects(self::exactly(2))
            ->method('getUuid')
            ->willReturn('a76c4a66-19bf-477e-99ad-40216ec96a12');

        $this->processo->expects(self::exactly(2))
            ->method('getUuid')
            ->willReturn('baa31c57-e5da-49a2-842d-f547437061f2');

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
