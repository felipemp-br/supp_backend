<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Triggers/Usuario/Trigger0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Usuario;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDto;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario\Trigger0005;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Triggers\Usuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|UsuarioDto $usuarioDto;

    private MockObject|UsuarioEntity $usuarioEntity;

    private TriggerInterface $trigger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioDto = $this->createMock(UsuarioDto::class);
        $this->usuarioEntity = $this->createMock(UsuarioEntity::class);

        $this->trigger = new Trigger0005(
            $this->authorizationChecker,
            $this->tokenStorage
        );
    }

    public function testUsuarioSemPermissao(): void
    {
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(false);

        $this->usuarioDto->expects(self::once())
            ->method('setEnabled')
            ->with(self::identicalTo(false));

        $this->trigger->execute($this->usuarioDto, $this->usuarioEntity, 'transaction');
    }

    public function testUsuarioComPermissao(): void
    {
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(false, true);

        $this->usuarioDto->expects(self::never())
            ->method('setEnabled')
            ->with(self::identicalTo(false));

        $this->trigger->execute($this->usuarioDto, $this->usuarioEntity, 'transaction');
    }
}
