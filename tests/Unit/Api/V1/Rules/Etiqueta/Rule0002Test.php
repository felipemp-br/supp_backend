<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Etiqueta/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Etiqueta;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Etiqueta\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoPessoaUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Etiqueta;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ClearableTokenStorageInterface $tokenStorage;

    private MockObject|EtiquetaEntity $etiquetaEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenInterface $token;

    private MockObject|Usuario $usuarioLogado;

    private MockObject|Usuario $usuarioVinculado;

    private MockObject|VinculacaoEtiqueta $vinculacaoEtiqueta;

    private MockObject|VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->etiquetaEntity = $this->createMock(EtiquetaEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioLogado = $this->createMock(Usuario::class);
        $this->usuarioVinculado = $this->createMock(Usuario::class);
        $this->vinculacaoEtiqueta = $this->createMock(VinculacaoEtiqueta::class);
        $this->vinculacaoEtiquetaRepository = $this->createMock(VinculacaoEtiquetaRepository::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->vinculacaoEtiquetaRepository
        );
    }

    public function testUsuarioNaoPodeExcluir(): void
    {
        $this->authorizationChecker->expects(self::once())
        ->method('isGranted')
        ->willReturn(false);

        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->vinculacaoEtiqueta->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioVinculado);

        $this->vinculacaoEtiquetaRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->vinculacaoEtiqueta);

        $this->etiquetaEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->etiquetaEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->etiquetaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioPodeExcluir(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->usuarioLogado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->token->expects(self::once())
            ->method('getUser')
            ->willReturn($this->usuarioLogado);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->vinculacaoEtiqueta->expects(self::once())
            ->method('getUsuario')
            ->willReturn($this->usuarioVinculado);

        $this->vinculacaoEtiquetaRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->vinculacaoEtiqueta);

        $this->etiquetaEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->etiquetaEntity->expects(self::once())
            ->method('getId')
            ->willReturn(10);


        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->etiquetaEntity, 'transaction'));
    }
}
