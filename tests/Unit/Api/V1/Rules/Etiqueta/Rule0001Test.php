<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Etiqueta/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Etiqueta;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Etiqueta\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Etiqueta;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ClearableTokenStorageInterface $tokenStorage;

    private MockObject|EtiquetaEntity $etiquetaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|Usuario $usuario;

    private MockObject|VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->etiquetaEntity = $this->createMock(EtiquetaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuario = $this->createMock(Usuario::class);
        $this->vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->vinculacaoPessoaUsuarioRepository
        );
    }

    public function testUsuarioNaoConveniado(): void
    {
        $this->authorizationChecker->expects(self::once())
        ->method('isGranted')
        ->willReturn(false);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuario);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->etiquetaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioConveniado(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuario);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn([[]]);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->etiquetaEntity, 'transaction'));
    }
}
