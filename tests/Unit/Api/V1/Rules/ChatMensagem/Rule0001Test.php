<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatMensagem/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ChatMensagemDto $chatMensagemDto;

    private MockObject|ChatMensagemEntity $chatMensagemEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioChat;

    private MockObject|Usuario $usuarioLogado;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chatMensagemDto = $this->createMock(ChatMensagemDto::class);
        $this->chatMensagemEntity = $this->createMock(ChatMensagemEntity::class);
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
    public function testUsuarioDiferente(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->chatMensagemDto->expects(self::exactly(2))
            ->method('getUsuarioTo')
            ->willReturn($this->usuarioChat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->chatMensagemDto, $this->chatMensagemEntity, 'transaction'));
    }

    public function testMesmoUsuario(): void
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

        $this->chatMensagemDto->expects(self::exactly(2))
            ->method('getUsuarioTo')
            ->willReturn($this->usuarioChat);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatMensagemDto, $this->chatMensagemEntity, 'transaction');
    }
}
