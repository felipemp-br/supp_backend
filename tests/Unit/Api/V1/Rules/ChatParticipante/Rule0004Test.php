<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatParticipante/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|Chat $chat;

    private MockObject|ChatParticipanteDto $chatParticipanteDto;

    private MockObject|ChatParticipanteEntity $chatParticipanteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioLogado;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->chat = $this->createMock(Chat::class);
        $this->chatParticipanteDto = $this->createMock(ChatParticipanteDto::class);
        $this->chatParticipanteEntity = $this->createMock(ChatParticipanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testParticipante(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->chat->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->chat->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioParticipante = $this->createMock(Usuario::class);
        $usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $participante = $this->createMock(ChatParticipanteEntity::class);

        $participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioParticipante);

        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(false);

        $participantes = new ArrayCollection();
        $participantes->add($participante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatParticipanteDto->expects(self::exactly(3))
            ->method('getChat')
            ->willReturn($this->chat);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->chatParticipanteDto, $this->chatParticipanteEntity, 'transaction');
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testAdministrador(): void
    {
        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->chat->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->chat->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioParticipante = $this->createMock(Usuario::class);
        $usuarioParticipante->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $participante = $this->createMock(ChatParticipanteEntity::class);

        $participante->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioParticipante);

        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes = new ArrayCollection();
        $participantes->add($participante);

        $this->chat->expects(self::once())
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chatParticipanteDto->expects(self::exactly(3))
            ->method('getChat')
            ->willReturn($this->chat);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->chatParticipanteDto, $this->chatParticipanteEntity, 'transaction')
        );
    }
}
