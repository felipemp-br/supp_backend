<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Sigilo/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Sigilo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Sigilo as SigiloDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Sigilo\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Sigilo as SigiloEntity;
use SuppCore\AdministrativoBackend\Entity\TipoSigilo;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Sigilo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SigiloDto $sigiloDto;

    private MockObject|SigiloEntity $sigiloEntity;

    private MockObject|TipoSigilo $tipoSigilo;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorage $tokenStorage;

    private MockObject|Usuario $usuarioToken;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->sigiloDto = $this->createMock(SigiloDto::class);
        $this->sigiloEntity = $this->createMock(SigiloEntity::class);
        $this->tipoSigilo = $this->createMock(TipoSigilo::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioToken = $this->createMock(Usuario::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->tokenStorage
        );
    }

    public function testSemPoderes(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(0);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->tipoSigilo->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(1);

        $this->sigiloDto->expects(self::once())
            ->method('getTipoSigilo')
            ->willReturn($this->tipoSigilo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoderes(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->tipoSigilo->expects(self::once())
            ->method('getNivelAcesso')
            ->willReturn(1);

        $this->sigiloDto->expects(self::once())
            ->method('getTipoSigilo')
            ->willReturn($this->tipoSigilo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->sigiloDto, $this->sigiloEntity, 'transaction'));
    }
}
