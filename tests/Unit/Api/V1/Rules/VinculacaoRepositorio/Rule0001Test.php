<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/VinculacaoRepositorio/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoRepositorio;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio as VinculacaoRepositorioDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRepositorio\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio as VinculacaoRepositorioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\VinculacaoRepositorio;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private MockObject|VinculacaoRepositorioDto $vinculacaoRepositorioDto;

    private MockObject|VinculacaoRepositorioEntity $vinculacaoRepositorioEntity;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->vinculacaoRepositorioDto = $this->createMock(VinculacaoRepositorioDto::class);
        $this->vinculacaoRepositorioEntity = $this->createMock(VinculacaoRepositorioEntity::class);

        $this->rule = new Rule0001(
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->coordenadorService
        );
    }

    public function testRepositorioTipoIndividualSemPermissao(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
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

        $usuario = $this->createMock(Usuario::class);
        $usuario->expects(self::once())
            ->method('getId')
            ->willReturn(20);

        $this->vinculacaoRepositorioDto->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuario);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRepositorioTipoIndividualComPermissao(): void
    {
        $usuarioToken = $this->createMock(Usuario::class);
        $usuarioToken->expects(self::once())
            ->method('getId')
            ->willReturn(20);

        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getUser')
            ->willReturn($usuarioToken);

        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $usuario = $this->createMock(Usuario::class);
        $usuario->expects(self::once())
            ->method('getId')
            ->willReturn(20);

        $this->vinculacaoRepositorioDto->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuario);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction')
        );
    }

    public function testRepositorioTipoSetorSemPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->vinculacaoRepositorioDto->expects(self::exactly(4))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRepositorioTipoSetorComPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->vinculacaoRepositorioDto->expects(self::exactly(4))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction')
        );
    }

    public function testRepositorioTipoUnidadeSemPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->vinculacaoRepositorioDto->expects(self::exactly(3))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRepositorioTipoUnidadeComPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->vinculacaoRepositorioDto->expects(self::exactly(3))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction')
        );
    }

    public function testRepositorioTipoOrgaoCentralSemPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $this->vinculacaoRepositorioDto->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRepositorioTipoOrgaoCentralComPermissao(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $this->vinculacaoRepositorioDto->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->vinculacaoRepositorioDto, $this->vinculacaoRepositorioEntity, 'transaction')
        );
    }
}
