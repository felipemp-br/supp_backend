<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Processo/Rule0007Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Processo\Rule0007;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0007Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|Usuario $usuarioToken;

    private MockObject|VinculacaoPessoaUsuarioRepository $vinculacaoPessoaUsuarioRepository;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioToken = $this->createMock(Usuario::class);
        $this->vinculacaoPessoaUsuarioRepository = $this->createMock(VinculacaoPessoaUsuarioRepository::class);

        $this->rule = new Rule0007(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->vinculacaoPessoaUsuarioRepository,
            $this->parameterBag
        );
    }

    public function testUsuarioNaoVinculacado(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->token->expects(self::once())
            ->method('getRoleNames')
            ->willReturn(['ROLE_USER']);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn(['ROLE_PROCESSO']);

        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->processoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     * @throws Exception
     */
    public function testUsuarioVinculado(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->token->expects(self::once())
            ->method('getRoleNames')
            ->willReturn(['ROLE_USER']);

        $this->parameterBag->expects(self::once())
            ->method('get')
            ->willReturn(['ROLE_PROCESSO']);

        $vinculacaoPessoaUsuario = $this->createMock(VinculacaoPessoaUsuario::class);
        $this->vinculacaoPessoaUsuarioRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($vinculacaoPessoaUsuario);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioToken);

        $this->tokenStorage->expects(self::exactly(2))
            ->method('getToken')
            ->willReturn($this->token);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->processoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testRoleColaborador(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        self::assertTrue($this->rule->validate(null, $this->processoEntity, 'transaction'));
    }
}
