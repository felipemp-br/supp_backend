<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ChatParticipante/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ChatParticipante\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Chat;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante as ChatParticipanteEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ChatParticipante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|Chat $chat;

    private MockObject|ChatParticipanteEntity $chatParticipanteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->chat = $this->createMock(Chat::class);
        $this->chatParticipanteEntity = $this->createMock(ChatParticipanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate
        );
    }

    /**
     * @throws RuleException
     */
    public function testVariosAdministradores(): void
    {
        $participantes = new ArrayCollection();

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes->add($participante);

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes->add($participante);

        $this->chat->expects(self::exactly(2))
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chat->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->chatParticipanteEntity->expects(self::exactly(3))
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate(null, $this->chatParticipanteEntity, 'transaction')
        );
    }

    public function testUnicoAdministrador(): void
    {
        $participantes = new ArrayCollection();

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $participantes->add($participante);

        $participante = $this->createMock(ChatParticipanteEntity::class);
        $participante->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(false);

        $participantes->add($participante);

        $this->chat->expects(self::exactly(2))
            ->method('getParticipantes')
            ->willReturn($participantes);

        $this->chat->expects(self::once())
            ->method('getGrupo')
            ->willReturn(true);

        $this->chatParticipanteEntity->expects(self::exactly(3))
            ->method('getChat')
            ->willReturn($this->chat);

        $this->chatParticipanteEntity->expects(self::once())
            ->method('getAdministrador')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->chatParticipanteEntity, 'transaction');
    }
}
