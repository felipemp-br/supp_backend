<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatParticipante/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ChatParticipanteEntity $chatParticipanteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioChat;

    private MockObject|Usuario $usuarioLogado;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatParticipanteEntity = $this->createMock(ChatParticipanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioChat = $this->createMock(Usuario::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    /**
     * @throws RuleException
     */
    public function testProprioParticipante(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->chatParticipanteEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAdministradorChat(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(7);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $usuarioParticipante = $this->createMock(Usuario::class);
        $usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(7);

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioParticipante);

        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes = new ArrayCollection();
        $participantes->add($participante);

        $chat = $this->createMock(Chat::class);
        $chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getChat')
            ->willReturn($chat);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->chatParticipanteEntity, 'transaction'));
    }

    public function testSemPermissaoParaExcluir(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(7);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $usuarioParticipante = $this->createMock(Usuario::class);
        $usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(7);

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioParticipante);

        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(false);

        $participantes = new ArrayCollection();
        $participantes->add($participante);

        $chat = $this->createMock(Chat::class);
        $chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getChat')
            ->willReturn($chat);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->chatParticipanteEntity, 'transaction');
    }
}
