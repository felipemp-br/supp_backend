<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento as DesentranhamentoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Repository\TramitacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|DesentranhamentoDto $desentranhamentoDto;

    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|Processo $processoDestino;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TramitacaoRepository $tramitacaoRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->desentranhamentoDto = $this->createMock(DesentranhamentoDto::class);
        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->processoDestino = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tramitacaoRepository = $this->createMock(TramitacaoRepository::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->tramitacaoRepository,
        );
    }

    public function testProcessoTramitacao(): void
    {
        $this->tramitacaoRepository->expects(self::once())
            ->method('findPendenteExternaProcesso')
            ->willReturn(true);

        $this->processoDestino->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getProcessoDestino')
            ->willReturn($this->processoDestino);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testProcessoSemTramitacao(): void
    {
        $this->tramitacaoRepository->expects(self::once())
            ->method('findPendenteExternaProcesso')
            ->willReturn(false);

        $this->processoDestino->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->desentranhamentoDto->expects(self::once())
            ->method('getProcessoDestino')
            ->willReturn($this->processoDestino);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->desentranhamentoDto, $this->desentranhamentoEntity, 'transaction')
        );
    }
}
