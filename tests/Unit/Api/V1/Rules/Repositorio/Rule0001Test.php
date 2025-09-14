<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Repositorio/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Repositorio;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Repositorio\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Repositorio as RepositorioEntity;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Repositorio;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RepositorioEntity $repositorioEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->repositorioEntity = $this->createMock(RepositorioEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0001(
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->coordenadorService,
        );
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorSetor(): void
    {
        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoRepositorio = $this->createMock(VinculacaoRepositorio::class);
        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacoesRepositorios = new ArrayCollection();
        $vinculacoesRepositorios->add($vinculacaoRepositorio);

        $this->repositorioEntity->expects(self::any())
            ->method('getVinculacoesRepositorios')
            ->willReturn($vinculacoesRepositorios);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->repositorioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorUnidade(): void
    {
        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $vinculacaoRepositorio = $this->createMock(VinculacaoRepositorio::class);
        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacoesRepositorios = new ArrayCollection();
        $vinculacoesRepositorios->add($vinculacaoRepositorio);

        $this->repositorioEntity->expects(self::any())
            ->method('getVinculacoesRepositorios')
            ->willReturn($vinculacoesRepositorios);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->repositorioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorOrgaoCentral(): void
    {
        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $vinculacaoRepositorio = $this->createMock(VinculacaoRepositorio::class);
        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacoesRepositorios = new ArrayCollection();
        $vinculacoesRepositorios->add($vinculacaoRepositorio);

        $this->repositorioEntity->expects(self::any())
            ->method('getVinculacoesRepositorios')
            ->willReturn($vinculacoesRepositorios);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->repositorioEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioVinculado(): void
    {
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

        $vinculacaoRepositorio = $this->createMock(VinculacaoRepositorio::class);
        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesRepositorios = new ArrayCollection();
        $vinculacoesRepositorios->add($vinculacaoRepositorio);

        $this->repositorioEntity->expects(self::any())
            ->method('getVinculacoesRepositorios')
            ->willReturn($vinculacoesRepositorios);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->repositorioEntity, 'transaction'));
    }

    public function testUsuarioSemPermissao(): void
    {
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

        $vinculacaoRepositorio = $this->createMock(VinculacaoRepositorio::class);
        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacaoRepositorio->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesRepositorios = new ArrayCollection();
        $vinculacoesRepositorios->add($vinculacaoRepositorio);

        $this->repositorioEntity->expects(self::any())
            ->method('getVinculacoesRepositorios')
            ->willReturn($vinculacoesRepositorios);

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

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->repositorioEntity, 'transaction');
    }
}
