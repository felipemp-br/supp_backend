<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoProcesso/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoProcesso\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|VinculacaoProcessoDto $vinculacaoProcessoDto;

    private MockObject|VinculacaoProcessoEntity $vinculacaoProcessoEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->vinculacaoProcessoDto = $this->createMock(VinculacaoProcessoDto::class);
        $this->vinculacaoProcessoEntity = $this->createMock(VinculacaoProcessoEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderProcesso(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    public function testUsuarioSemPoderProcessoVinculado(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $processoVinculado = $this->createMock(Processo::class);
        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($processoVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    public function testUsuarioSemPoderClassificacaoProcesso(): void
    {
        $this->authorizationChecker->expects(self::exactly(3))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, true, false);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $processoVinculado = $this->createMock(Processo::class);
        $this->vinculacaoProcessoDto->expects(self::once())
            ->method('getProcessoVinculado')
            ->willReturn($processoVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    public function testUsuarioSemPoderClassificacaoProcessoVinculado(): void
    {
        $this->authorizationChecker->expects(self::exactly(4))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, true, true, false);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $classificacaoProcessoVinculado = $this->createMock(Classificacao::class);
        $classificacaoProcessoVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $processoVinculado = $this->createMock(Processo::class);
        $processoVinculado->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacaoProcessoVinculado);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcessoVinculado')
            ->willReturn($processoVinculado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoder(): void
    {
        $this->authorizationChecker->expects(self::exactly(4))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, true, true, true);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $classificacaoProcessoVinculado = $this->createMock(Classificacao::class);
        $classificacaoProcessoVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $processoVinculado = $this->createMock(Processo::class);
        $processoVinculado->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacaoProcessoVinculado);

        $this->vinculacaoProcessoDto->expects(self::exactly(4))
            ->method('getProcessoVinculado')
            ->willReturn($processoVinculado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoProcessoDto, $this->vinculacaoProcessoEntity, 'transaction')
        );
    }
}
