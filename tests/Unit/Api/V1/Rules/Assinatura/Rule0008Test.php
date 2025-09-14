<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assinatura/Rule0008Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura\Rule0008;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\X509Service;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0008Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0008Test extends TestCase
{
    private MockObject|AssinaturaEntity $assinaturaEntity;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuario;

    private MockObject|Usuario $usuarioToken;

    private MockObject|X509Service $x509Service;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assinaturaEntity = $this->createMock(AssinaturaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->usuarioToken = $this->createMock(Usuario::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->x509Service = $this->createMock(X509Service::class);

        $this->rule = new Rule0008(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->x509Service,
            $this->parameterBag
        );
    }

    /**
     * @throws RuleException
     */
    public function testAssinaturaA3(): void
    {
        $this->usuario->expects(self::once())
            ->method('getUsername')
            ->willReturn('USER');

        $this->usuarioToken->expects(self::once())
            ->method('getUsername')
            ->willReturn('USER');

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('*.agu.gov.br');

        $this->x509Service->expects(self::once())
            ->method('getCredentials')
            ->willReturn(['cn' => '*.agu.gov.br', 'username' => 'USER']);

        $this->assinaturaEntity->expects(self::once())
            ->method('getCriadoPor')
            ->willReturn($this->usuario);

        $this->assinaturaEntity->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn('PEM');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->assinaturaEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testAssinaturaA1(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getUsername')
            ->willReturn('USER');

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('*.agu.gov.br');

        $this->x509Service->expects(self::once())
            ->method('getCredentials')
            ->willReturn(['cn' => '*', 'username' => 'USER']);

        $this->assinaturaEntity->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn('PEM');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->assinaturaEntity, 'transaction'));
    }

    public function testNaoAssinado(): void
    {
        $this->usuarioToken->expects(self::once())
            ->method('getUsername')
            ->willReturn('USER');

        $this->token->expects(self::exactly(2))
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(3))
            ->method('getToken')
            ->willReturn($this->token);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn('*.agu.gov.br');

        $this->x509Service->expects(self::once())
            ->method('getCredentials')
            ->willReturn(['cn' => '*', 'username' => 'USERNAME']);

        $this->assinaturaEntity->expects(self::exactly(2))
            ->method('getCadeiaCertificadoPEM')
            ->willReturn('PEM');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->assinaturaEntity, 'transaction');
    }
}
