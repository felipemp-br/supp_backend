<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Chat/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Chat\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Chat;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|ChatDto $chatDto;

    private MockObject|ChatEntity $chatEntity;

    private MockObject|ChatParticipante $chatParticipante;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuario;

    private MockObject|Usuario $usuarioLogado;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatDto = $this->createMock(ChatDto::class);
        $this->chatEntity = $this->createMock(ChatEntity::class);
        $this->chatParticipante = $this->createMock(ChatParticipante::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    /**
     * @throws RuleException
     */
    public function testMesmoUsuario(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->chatParticipante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $this->chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->chatDto->expects(self::once())
            ->method('getParticipantes')
            ->willReturn([$this->chatParticipante]);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->chatDto, $this->chatEntity, 'transaction'));
    }

    public function testUsuarioDiferente(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(50);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->chatParticipante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $this->chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->chatDto->expects(self::once())
            ->method('getParticipantes')
            ->willReturn([$this->chatParticipante]);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatDto, $this->chatEntity, 'transaction');
    }
}
