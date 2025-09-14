<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lotacao/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lotacao as LotacaoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|Colaborador $colaborador;

    private MockObject|LotacaoDto $lotacaoDto;

    private MockObject|LotacaoEntity $lotacaoEntity;

    private MockObject|LotacaoRepository $lotacaoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Setor $setor;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->colaborador = $this->createMock(Colaborador::class);
        $this->lotacaoDto = $this->createMock(LotacaoDto::class);
        $this->lotacaoEntity = $this->createMock(LotacaoEntity::class);
        $this->lotacaoRepository = $this->createMock(LotacaoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setor = $this->createMock(Setor::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->lotacaoRepository
        );
    }

    public function testUsuarioJaLotado(): void
    {
        $this->lotacaoRepository->expects(self::once())
            ->method('findLotacaoBySetorAndColaborador')
            ->willReturn(true);

        $this->setor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->colaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->lotacaoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->lotacaoDto->expects(self::once())
            ->method('getColaborador')
            ->willReturn($this->colaborador);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->lotacaoDto, $this->lotacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioNaoLotado(): void
    {
        $this->lotacaoRepository->expects(self::once())
            ->method('findLotacaoBySetorAndColaborador')
            ->willReturn(false);

        $this->setor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->colaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->lotacaoDto->expects(self::once())
            ->method('getSetor')
            ->willReturn($this->setor);

        $this->lotacaoDto->expects(self::once())
            ->method('getColaborador')
            ->willReturn($this->colaborador);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->lotacaoDto, $this->lotacaoEntity, 'transaction'));
    }
}
