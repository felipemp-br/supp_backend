<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Usuario/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|UsuarioEntity $usuarioEntity;

    private MockObject|UsuarioEntity $usuarioToken;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioEntity = $this->createMock(UsuarioEntity::class);
        $this->usuarioToken = $this->createMock(UsuarioEntity::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    public function testMesmoUsuario(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->usuarioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioDiferente(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->usuarioEntity, 'transaction');
    }
}
