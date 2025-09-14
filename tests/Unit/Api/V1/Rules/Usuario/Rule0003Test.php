<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Usuario/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Usuario;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|UsuarioEntity $usuarioEntity;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->usuarioEntity = $this->createMock(UsuarioEntity::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->tokenStorage,
            $this->authorizationChecker,
            $this->coordenadorService
        );
    }

    /**
     * @throws RuleException
     */
    public function testResetSenhaComAdmin()
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->usuarioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testResetSenhaComMesmoUsuario()
    {
        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->usuarioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testResetSenhaComCoordenadorUnidade()
    {
        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes->add($lotacao);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->usuarioEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->usuarioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testResetSenhaComCoordenadorOrgaoCentral()
    {
        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes->add($lotacao);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->usuarioEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->usuarioEntity, 'transaction'));
    }

    public function testResetSemPermissao()
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->usuarioEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes->add($lotacao);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $this->usuarioEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->rulesTranslate->expects(self::atMost(1))
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->usuarioEntity, 'transaction');
    }
}
