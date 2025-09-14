<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0013Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0013;
use SuppCore\AdministrativoBackend\Entity\Assunto;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Entity\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0013Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0013Test extends TestCase
{
    private MockObject|ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|Processo $processo;

    private MockObject|Processo $processoVinculado;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    private TransactionManager $transactionManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->modalidadeVinculacaoProcesso = $this->createMock(ModalidadeVinculacaoProcesso::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->processoVinculado = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);

        $this->rule = new Rule0013(
            $this->rulesTranslate,
            $this->parameterBag,
            $this->transactionManager
        );
    }

    /**
     * @throws RuleException
     */
    public function testcriacaoProcessoBarramento(): void
    {
        $context = $this->createMock(Context::class);
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }

    public function testAssuntosDiferentes(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ANEXAÇÃO');

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('ANEXAÇÃO');

        $assuntos = new ArrayCollection();

        for ($i = 1; $i <= 10; ++$i) {
            $assuntoAdministrativo = $this->createMock(AssuntoAdministrativo::class);
            $assuntoAdministrativo->expects(self::once())
                ->method('getId')
                ->willReturn($i);

            $assunto = $this->createMock(Assunto::class);
            $assunto->expects(self::once())
                ->method('getAssuntoAdministrativo')
                ->willReturn($assuntoAdministrativo);

            $assuntos->add($assunto);
        }

        $this->processo->expects(self::once())
            ->method('getAssuntos')
            ->willReturn($assuntos);

        $assuntosProcessoVinculado = new ArrayCollection();

        for ($i = 10; $i <= 19; ++$i) {
            $assuntoAdministrativo = $this->createMock(AssuntoAdministrativo::class);
            $assuntoAdministrativo->expects(self::once())
                ->method('getId')
                ->willReturn($i);

            $assunto = $this->createMock(Assunto::class);
            $assunto->expects(self::once())
                ->method('getAssuntoAdministrativo')
                ->willReturn($assuntoAdministrativo);

            $assuntosProcessoVinculado->add($assunto);
        }

        $this->processoVinculado->expects(self::once())
            ->method('getAssuntos')
            ->willReturn($assuntosProcessoVinculado);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testAssuntosIguais(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('ANEXAÇÃO');

        $this->modalidadeVinculacaoProcesso->expects(self::once())
            ->method('getValor')
            ->willReturn('ANEXAÇÃO');

        $assuntos = new ArrayCollection();

        for ($i = 1; $i <= 10; ++$i) {
            $assuntoAdministrativo = $this->createMock(AssuntoAdministrativo::class);
            $assuntoAdministrativo->expects(self::exactly(2))
                ->method('getId')
                ->willReturn($i);

            $assunto = $this->createMock(Assunto::class);
            $assunto->expects(self::exactly(2))
                ->method('getAssuntoAdministrativo')
                ->willReturn($assuntoAdministrativo);

            $assuntos->add($assunto);
        }

        $this->processo->expects(self::once())
            ->method('getAssuntos')
            ->willReturn($assuntos);

        $this->processoVinculado->expects(self::once())
            ->method('getAssuntos')
            ->willReturn($assuntos);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($this->processoVinculado);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getModalidadeVinculacaoProcesso')
            ->willReturn($this->modalidadeVinculacaoProcesso);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
