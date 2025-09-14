<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Modelo/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Modelo;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Modelo\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Modelo as ModeloEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Modelo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|ModeloEntity $modeloEntity;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|TransactionManager $transactionManager;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->modeloEntity = $this->createMock(ModeloEntity::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->rule = new Rule0001(
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->coordenadorService,
            $this->parameterBag,
            $this->authorizationChecker,
            $this->transactionManager
        );
    }

    /**
     * @throws RuleException|Exception
     */
    public function testRoleAdmin(): void
    {
        $this->transactionManager->expects(self::never())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('INDIVIDUAL');

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws RuleException|Exception
     */
    public function testModalidadeModeloPermitido(): void
    {
        $this->transactionManager->expects(self::never())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::never())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('REGIONAL');

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws RuleException|Exception
     */
    public function testCoordenadorSetor(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws RuleException|Exception
     */
    public function testCoordenadorUnidade(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws RuleException|Exception
     */
    public function testCoordenadorOrgaoCentral(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws RuleException|Exception
     */
    public function testUsuarioVinculado(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $usuarioVinculado = $this->createMock(Usuario::class);
        $usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }

    /**
     * @throws Exception
     */
    public function testUsuarioSemPermissao(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $usuarioVinculado = $this->createMock(Usuario::class);
        $usuarioVinculado->expects(self::once())
            ->method('getId')
            ->willReturn(5);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacaoModelo->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

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

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(false);

        $this->coordenadorService->expects(self::exactly(2))
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::exactly(3))
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->modeloEntity, 'transaction');
    }

    /**
     * @throws RuleException|Exception
     */
    public function testContextoPromoverModelo(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($this->createMock(Context::class));

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $usuarioVinculado = $this->createMock(Usuario::class);
        $usuarioVinculado->expects(self::never())
            ->method('getId')
            ->willReturn(5);

        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::never())
            ->method('getId')
            ->willReturn(5);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::never())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::never())
            ->method('getToken')
            ->willReturn($token);

        $vinculacaoModelo = $this->createMock(VinculacaoModelo::class);
        $vinculacaoModelo->expects(self::never())
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesModelos = new ArrayCollection();
        $vinculacoesModelos->add($vinculacaoModelo);

        $this->modeloEntity->expects(self::any())
            ->method('getVinculacoesModelos')
            ->willReturn($vinculacoesModelos);

        $this->modeloEntity->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->modeloEntity, 'transaction'));
    }
}
