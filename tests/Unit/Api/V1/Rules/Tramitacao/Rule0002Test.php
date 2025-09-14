<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tramitacao/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setorAtual;

    private MockObject|Setor $setorDestino;

    private MockObject|TramitacaoDto $tramitacaoDto;

    private MockObject|TramitacaoEntity $tramitacaoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorAtual = $this->createMock(Setor::class);
        $this->setorDestino = $this->createMock(Setor::class);
        $this->tramitacaoDto = $this->createMock(TramitacaoDto::class);
        $this->tramitacaoEntity = $this->createMock(TramitacaoEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testNUPNoDestino(): void
    {
        $this->setorDestino->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->setorAtual->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setorAtual);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getSetorDestino')
            ->willReturn($this->setorDestino);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoDestino(): void
    {
        $this->setorDestino->expects(self::once())
            ->method('getId')
            ->willReturn(3);

        $this->setorAtual->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::once())
            ->method('getSetorAtual')
            ->willReturn($this->setorAtual);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->tramitacaoDto->expects(self::exactly(2))
            ->method('getSetorDestino')
            ->willReturn($this->setorDestino);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction'));
    }
}
