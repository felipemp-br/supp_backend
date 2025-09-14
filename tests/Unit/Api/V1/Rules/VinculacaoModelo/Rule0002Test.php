<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoModelo/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoModelo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoModelo\Rule0002;
use SuppCore\AdministrativoBackend\Entity\ModalidadeModelo;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Modelo;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoModelo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|Modelo $modelo;

    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|VinculacaoModeloDto $vinculacaoModeloDto;

    private MockObject|VinculacaoModeloEntity $vinculacaoModeloEntity;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->modelo = $this->createMock(Modelo::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->vinculacaoModeloDto = $this->createMock(VinculacaoModeloDto::class);
        $this->vinculacaoModeloEntity = $this->createMock(VinculacaoModeloEntity::class);

        $this->rule = new Rule0002(
            $this->authorizationChecker,
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->coordenadorService,
            $this->parameterBag
        );
    }

    /**
     * @throws RuleException
     */
    public function testModalidadeModeloPermitido(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('REGIONAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    /**
     * @throws RuleException
     */
    public function testModeloEmBrancoComUsuarioAdmin(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('EM BRANCO');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    public function testModeloEmBrancoSemUsuarioAdmin(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('EM BRANCO');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testModeloIndividualComPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('INDIVIDUAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $usuario = $this->createMock(UsuarioEntity::class);
        $usuario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->vinculacaoModeloDto->expects(self::exactly(3))
            ->method('getUsuario')
            ->willReturn($usuario);

        $usuarioToken = $this->createMock(UsuarioEntity::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    public function testModeloIndividualSemPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('INDIVIDUAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $usuario = $this->createMock(UsuarioEntity::class);
        $usuario->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->vinculacaoModeloDto->expects(self::exactly(3))
            ->method('getUsuario')
            ->willReturn($usuario);

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

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testModeloSetorComPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(SetorEntity::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->vinculacaoModeloDto->expects(self::exactly(5))
            ->method('getSetor')
            ->willReturn($setor);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    public function testModeloSetorSemPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(SetorEntity::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->vinculacaoModeloDto->expects(self::exactly(5))
            ->method('getSetor')
            ->willReturn($setor);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testModeloUnidadeComPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('LOCAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->vinculacaoModeloDto->expects(self::exactly(4))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    public function testModeloUnidadeSemPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('LOCAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->vinculacaoModeloDto->expects(self::exactly(4))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testModeloOrgaoCentralComPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $this->vinculacaoModeloDto->expects(self::exactly(3))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction')
        );
    }

    public function testModeloOrgaoCentralSemPermissao(): void
    {
        $this->parameterBag->expects(self::exactly(4))
            ->method('get')
            ->willReturnOnConsecutiveCalls('INDIVIDUAL', 'LOCAL', 'NACIONAL', 'EM BRANCO');

        $modalidadeModelo = $this->createMock(ModalidadeModelo::class);
        $modalidadeModelo->expects(self::once())
            ->method('getValor')
            ->willReturn('NACIONAL');

        $this->modelo->expects(self::once())
            ->method('getModalidadeModelo')
            ->willReturn($modalidadeModelo);

        $this->vinculacaoModeloDto->expects(self::once())
            ->method('getModelo')
            ->willReturn($this->modelo);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $this->vinculacaoModeloDto->expects(self::exactly(3))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoModeloDto, $this->vinculacaoModeloEntity, 'transaction');
    }
}
