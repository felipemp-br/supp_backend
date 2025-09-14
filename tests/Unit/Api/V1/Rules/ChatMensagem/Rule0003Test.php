<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatMensagem/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|ChatMensagemEntity $chatMensagemEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioAutor;

    private MockObject|Usuario $usuarioChat;

    private MockObject|Usuario $usuarioLogado;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatMensagemEntity = $this->createMock(ChatMensagemEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioAutor = $this->createMock(Usuario::class);
        $this->usuarioChat = $this->createMock(Usuario::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    /**
     * @throws RuleException
     */
    public function testAutorChat(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioAutor->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->chatMensagemEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioAutor);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->chatMensagemEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAdministradorChat(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioAutor->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $chatParticipante = $this->createMock(ChatParticipante::class);
        $chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $chatParticipante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes = new ArrayCollection();
        $participantes->add($chatParticipante);

        $chat = $this->createMock(Chat::class);
        $chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatMensagemEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioAutor);

        $this->chatMensagemEntity->expects(self::once())
            ->method('getChat')
            ->willReturn($chat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->chatMensagemEntity, 'transaction'));
    }

    public function testSemPermissaoParaExcluir(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioAutor->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $chatParticipante = $this->createMock(ChatParticipante::class);
        $chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $chatParticipante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(false);

        $participantes = new ArrayCollection();
        $participantes->add($chatParticipante);

        $chat = $this->createMock(Chat::class);
        $chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatMensagemEntity->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioAutor);

        $this->chatMensagemEntity->expects(self::once())
            ->method('getChat')
            ->willReturn($chat);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->chatMensagemEntity, 'transaction');
    }
}
