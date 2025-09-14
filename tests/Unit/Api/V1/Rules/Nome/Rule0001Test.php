<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Nome/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Nome;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Nome as NomeDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Nome\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Nome as NomeEntity;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Nome;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|NomeDto $nomeDto;

    private MockObject|NomeEntity $nomeEntity;

    private MockObject|Pessoa $pessoa;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->nomeDto = $this->createMock(NomeDto::class);
        $this->nomeEntity = $this->createMock(NomeEntity::class);
        $this->pessoa = $this->createMock(Pessoa::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testPessoaValidada(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(true);

        $this->nomeDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->nomeDto, $this->nomeEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testPessoaNaoValidada(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->pessoa->expects(self::once())
            ->method('getPessoaValidada')
            ->willReturn(false);

        $this->nomeDto->expects(self::once())
            ->method('getPessoa')
            ->willReturn($this->pessoa);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->nomeDto, $this->nomeEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAdministrador(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->nomeDto, $this->nomeEntity, 'transaction')
        );
    }
}
