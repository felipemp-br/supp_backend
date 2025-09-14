<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Afastamento/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Afastamento;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Afastamento\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Afastamento as AfastamentoEntity;
use SuppCore\AdministrativoBackend\Entity\Colaborador;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
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
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Afastamento;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|AfastamentoEntity $afastamentoEntity;

    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afastamentoEntity = $this->createMock(AfastamentoEntity::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->authorizationChecker,
            $this->tokenStorage,
            $this->coordenadorService
        );
    }

    /**
     * @throws RuleException
     */
    public function testDeleteAfastamentoComAdmin(): void
    {
        $this->authorizationChecker->expects(self::exactly(1))
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->afastamentoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testDeleteAfastamentoMesmoUsuario(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(1);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::exactly(1))
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::exactly(1))
            ->method('getToken')
            ->willReturn($token);

        $usuario = $this->createMock(Usuario::class);
        $usuario->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(1);

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::any())
            ->method('getUsuario')
            ->willReturn($usuario);

        $this->afastamentoEntity->expects(self::any())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->afastamentoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testDeleteAfastamentoComCoordenadorSetor(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::any())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::any())
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes[] = $lotacao;

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::any())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(10);

        $colaborador->expects(self::exactly(1))
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->afastamentoEntity->expects(self::any())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->coordenadorService->expects(self::any())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->afastamentoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testDeleteAfastamentoComCoordenadorUnidade(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::any())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::any())
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes[] = $lotacao;

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::any())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(10);

        $colaborador->expects(self::exactly(1))
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->afastamentoEntity->expects(self::any())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->coordenadorService->expects(self::any())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->afastamentoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testDeleteAfastamentoComCoordenadorOrgaoCentral(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::any())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::any())
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes[] = $lotacao;

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::any())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(10);

        $colaborador->expects(self::exactly(1))
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->afastamentoEntity->expects(self::any())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->coordenadorService->expects(self::any())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::exactly(0))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->afastamentoEntity, 'transaction'));
    }

    public function testDeleteAfastamentoSemCoordenador(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::any())
            ->method('getId')
            ->willReturn(2);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::any())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::any())
            ->method('getToken')
            ->willReturn($token);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $lotacao = $this->createMock(Lotacao::class);
        $lotacao->expects(self::any())
            ->method('getSetor')
            ->willReturn($setor);

        $lotacoes = new ArrayCollection();
        $lotacoes[] = $lotacao;

        $colaborador = $this->createMock(Colaborador::class);
        $colaborador->expects(self::any())
            ->method('getLotacoes')
            ->willReturn($lotacoes);

        $usuarioColaborador = $this->createMock(Usuario::class);
        $usuarioColaborador->expects(self::exactly(1))
            ->method('getId')
            ->willReturn(10);

        $colaborador->expects(self::exactly(1))
            ->method('getUsuario')
            ->willReturn($usuarioColaborador);

        $this->afastamentoEntity->expects(self::any())
            ->method('getColaborador')
            ->willReturn($colaborador);

        $this->rulesTranslate->expects(self::atMost(1))
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::expectException(RuleException::class);
        $this->rule->validate(null, $this->afastamentoEntity, 'transaction');
    }
}
