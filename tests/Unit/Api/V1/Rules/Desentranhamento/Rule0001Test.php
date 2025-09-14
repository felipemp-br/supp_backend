<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Desentranhamento/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Desentranhamento\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Desentranhamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|DesentranhamentoEntity $desentranhamentoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioToken;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->desentranhamentoEntity = $this->createMock(DesentranhamentoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioToken = $this->createMock(Usuario::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    public function testUsuarioSemPoder(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(0);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->desentranhamentoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoder(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->desentranhamentoEntity, 'transaction'));
    }
}
