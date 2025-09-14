<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tramitacao/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|Processo $processo;

    private MockObject|ProcessoRepository $processoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TramitacaoDto $tramitacaoDto;

    private MockObject|TramitacaoEntity $tramitacaoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->processo = $this->createMock(Processo::class);
        $this->processoRepository = $this->createMock(ProcessoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tramitacaoDto = $this->createMock(TramitacaoDto::class);
        $this->tramitacaoEntity = $this->createMock(TramitacaoEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->processoRepository
        );
    }

    public function testNUPEmTramitacao(): void
    {
        $this->processoRepository->expects(self::once())
            ->method('findProcessoEmTramitacao')
            ->willReturn(true);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoEstaEmTramitacao(): void
    {
        $this->processoRepository->expects(self::once())
            ->method('findProcessoEmTramitacao')
            ->willReturn(false);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction'));
    }
}
