<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lotacao/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao\Rule0005;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|LotacaoEntity $lotacaoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->lotacaoEntity = $this->createMock(LotacaoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate,
            $this->authorizationChecker,
            $this->tokenStorage,
            $this->coordenadorService
        );
    }

    /**
     * @throws RuleException
     */
    public function testUpdateLotacaoComAdmin(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->lotacaoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUpdateLotacaoProprioUsuario(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $usuarioToken = $this->createMock(Usuario::class);
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

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->lotacaoEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->lotacaoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUpdateLotacaoComCoordenadorUnidade(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
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

        $unidade = $this->createMock(Setor::class);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::once())
            ->method('getUnidade')
            ->willReturn($unidade);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->lotacaoEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->lotacaoEntity->expects(self::once())
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->lotacaoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUpdateLotacaoComCoordenadorOrgaoCentral(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
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

        $orgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($orgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->lotacaoEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->lotacaoEntity->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->lotacaoEntity, 'transaction'));
    }

    public function testUpdateLotacaoNaoCoordenador(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
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

        $orgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($orgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::once())
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->lotacaoEntity->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $this->lotacaoEntity->expects(self::once())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->lotacaoEntity, 'transaction');
    }
}
