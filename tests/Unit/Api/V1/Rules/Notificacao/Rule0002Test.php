<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Notificacao/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Notificacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Notificacao\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Notificacao as NotificacaoEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Notificacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|NotificacaoEntity $notificacaoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $destinatario;

    private MockObject|Usuario $usuarioToken;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->destinatario = $this->createMock(Usuario::class);
        $this->notificacaoEntity = $this->createMock(NotificacaoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioToken = $this->createMock(Usuario::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    public function testUsuarioNaoDestinatario(): void
    {
        $this->destinatario->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->notificacaoEntity->expects(self::once())
            ->method('getDestinatario')
            ->willReturn($this->destinatario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->notificacaoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioDestinatario(): void
    {
        $this->destinatario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->notificacaoEntity->expects(self::once())
            ->method('getDestinatario')
            ->willReturn($this->destinatario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->notificacaoEntity, 'transaction'));
    }
}
