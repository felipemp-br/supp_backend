<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lembrete/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lembrete;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete as LembreteDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lembrete\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Lembrete as LembreteEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lembrete;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|LembreteDto $lembreteDto;

    private MockObject|LembreteEntity $lembreteEntity;
    private MockObject|Processo $processo;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->lembreteDto = $this->createMock(LembreteDto::class);
        $this->lembreteEntity = $this->createMock(LembreteEntity::class);
        $this->processo = $this->createMock(Processo::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderesProcesso(): void
    {
        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->lembreteDto->expects(self::exactly(2))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->lembreteDto, $this->lembreteEntity, 'transaction');
    }

    /**
     * @throws Exception
     */
    public function testUsuarioSemPoderesClassificacao(): void
    {
        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->lembreteDto->expects(self::exactly(5))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->lembreteDto, $this->lembreteEntity, 'transaction');
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testUsuarioComPoderes(): void
    {
        $this->processo->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->lembreteDto->expects(self::exactly(5))
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->lembreteDto, $this->lembreteEntity, 'transaction'));
    }
}
