<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatParticipante/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|Chat $chat;

    private MockObject|ChatParticipanteDto $chatParticipanteDto;

    private MockObject|ChatParticipanteEntity $chatParticipanteEntity;

    private MockObject|ChatParticipanteEntity $participante;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|Usuario $usuarioChat;

    private MockObject|Usuario $usuarioParticipante;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chat = $this->createMock(Chat::class);
        $this->chatParticipanteDto = $this->createMock(ChatParticipanteDto::class);
        $this->chatParticipanteEntity = $this->createMock(ChatParticipanteEntity::class);
        $this->participante = $this->createMock(ChatParticipanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->usuarioChat = $this->createMock(Usuario::class);
        $this->usuarioParticipante = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    /**
     * @throws RuleException
     */
    public function testNaoParticipante(): void
    {
        $this->usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(15);

        $this->participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioParticipante);

        $participantes = new ArrayCollection();
        $participantes->add($this->participante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->chatParticipanteDto->expects(self::once())
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatParticipanteDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->chatParticipanteDto, $this->chatParticipanteEntity, 'transaction')
        );
    }

    public function testParticipante(): void
    {
        $this->usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioParticipante);

        $participantes = new ArrayCollection();
        $participantes->add($this->participante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->usuarioChat->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $this->chatParticipanteDto->expects(self::once())
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatParticipanteDto->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioChat);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatParticipanteDto, $this->chatParticipanteEntity, 'transaction');
    }
}
