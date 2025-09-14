<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Relatorio/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Relatorio;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Relatorio\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Relatorio;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|RelatorioEntity $relatorioEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->relatorioEntity = $this->createMock(RelatorioEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->authorizationChecker,
            $this->tokenStorage
        );
    }

    public function testUsuarioSemPoderes(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($token);

        $this->relatorioEntity->expects(self::once())
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->relatorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioAdministrador(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->relatorioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testMesmoUsuario(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $criadoPor = $this->createMock(Usuario::class);
        $criadoPor->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($token);

        $this->relatorioEntity->expects(self::once())
            ->method('getCriadoPor')
            ->willReturn($criadoPor);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->relatorioEntity, 'transaction'));
    }
}
