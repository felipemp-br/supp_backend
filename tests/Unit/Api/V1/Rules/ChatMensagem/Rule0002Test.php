<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatMensagem/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatMensagem\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatMensagem as ChatMensagemEntity;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatMensagem;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|Chat $chat;

    private MockObject|ChatMensagemDto $chatMensagemDto;

    private MockObject|ChatMensagemEntity $chatMensagemEntity;

    private MockObject|ChatParticipante $chatParticipante;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Usuario $usuario;

    private MockObject|Usuario $usuarioParticipante;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chat = $this->createMock(Chat::class);
        $this->chatMensagemDto = $this->createMock(ChatMensagemDto::class);
        $this->chatMensagemEntity = $this->createMock(ChatMensagemEntity::class);
        $this->chatParticipante = $this->createMock(ChatParticipante::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->usuarioParticipante = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
        );
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioParticipante(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioParticipante);

        $participantes = new ArrayCollection();
        $participantes->add($this->chatParticipante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatMensagemDto->expects(self::once())
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatMensagemDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->chatMensagemDto, $this->chatMensagemEntity, 'transaction'));
    }

    public function testUsuarioNaoParticipante(): void
    {
        $this->usuario->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->chatParticipante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioParticipante);

        $participantes = new ArrayCollection();
        $participantes->add($this->chatParticipante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatMensagemDto->expects(self::once())
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatMensagemDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatMensagemDto, $this->chatMensagemEntity, 'transaction');
    }
}
