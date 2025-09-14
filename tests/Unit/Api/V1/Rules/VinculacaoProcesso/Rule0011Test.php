<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0011Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0011;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0011Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0011Test extends TestCase
{
    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TransactionManager $transactionManager;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private MockObject|VinculacaoProcessoRepository $vinculacaoProcessoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);
        $this->vinculacaoProcessoRepository = $this->createMock(VinculacaoProcessoRepository::class);

        $this->rule = new Rule0011(
            $this->rulesTranslate,
            $this->vinculacaoProcessoRepository,
            $this->transactionManager
        );
    }

    public function testNUPVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('findByProcessoVinculado')
            ->willReturn(true);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoProcessoDto->expects(self::once())
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
    public function testNUPNaoVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->vinculacaoProcessoRepository->expects(self::once())
            ->method('findByProcessoVinculado')
            ->willReturn(false);

        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }

    /**
     * @throws RuleException
     */
    public function testContextRemessaDocumentoAvulso(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($this->createMock(Context::class));

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
