<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Usuario/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|UserPasswordHasherInterface $passwordHasher;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|UsuarioDTO $usuarioDto;

    private MockObject|UsuarioEntity $usuarioEntity;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioDto = $this->createMock(UsuarioDTO::class);
        $this->usuarioEntity = $this->createMock(UsuarioEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->passwordHasher
        );
    }

    /**
     * @throws RuleException
     */
    public function testSemAlterarSenha()
    {
        $this->usuarioDto->expects(self::once())
            ->method('getPlainPassword')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->usuarioDto, $this->usuarioEntity, 'transaction'));
    }

    public function testUsuarioDiferente()
    {
        $this->usuarioDto->expects(self::once())
            ->method('getPlainPassword')
            ->willReturn('password');

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(123);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->usuarioDto, $this->usuarioEntity, 'transaction');
    }

    public function testSenhaAtualNaoInformada()
    {
        $this->usuarioDto->expects(self::once())
            ->method('getPlainPassword')
            ->willReturn('password');

        $this->usuarioDto->expects(self::once())
            ->method('getCurrentPlainPassword')
            ->willReturn(null);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->usuarioDto, $this->usuarioEntity, 'transaction');
    }

    public function testSenhaAtualInvalida()
    {
        $this->usuarioDto->expects(self::once())
            ->method('getPlainPassword')
            ->willReturn('password');

        $this->usuarioDto->expects(self::exactly(2))
            ->method('getCurrentPlainPassword')
            ->willReturn('password');

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $this->passwordHasher->expects(self::once())
            ->method('isPasswordValid')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->usuarioDto, $this->usuarioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testSenhaAtualValida()
    {
        $this->usuarioDto->expects(self::once())
            ->method('getPlainPassword')
            ->willReturn('password');

        $this->usuarioDto->expects(self::exactly(2))
            ->method('getCurrentPlainPassword')
            ->willReturn('password');

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $this->passwordHasher->expects(self::once())
            ->method('isPasswordValid')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->usuarioDto, $this->usuarioEntity, 'transaction'));
    }
}
