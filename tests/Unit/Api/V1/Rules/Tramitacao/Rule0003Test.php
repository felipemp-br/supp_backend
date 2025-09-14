<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Tramitacao/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Tramitacao\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tramitacao as TramitacaoEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Tramitacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TramitacaoDto $tramitacaoDto;

    private MockObject|TramitacaoEntity $tramitacaoEntity;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tramitacaoDto = $this->createMock(TramitacaoDto::class);
        $this->tramitacaoEntity = $this->createMock(TramitacaoEntity::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->vinculacaoProcessoRepository = $this->createMock(VinculacaoProcessoRepository::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->vinculacaoProcessoRepository,
            $this->transactionManager
        );
    }

    public function testNUPEstaVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('estaApensada')
            ->willReturn(true);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testNUPNaoEstaVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $processo = $this->createMock(Processo::class);
        $processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('estaApensada')
            ->willReturn(false);

        $this->tramitacaoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCriacaoTramitacaoProcessoApensado(): void
    {
        $context = $this->createMock(Context::class);
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->tramitacaoDto, $this->tramitacaoEntity, 'transaction'));
    }
}
