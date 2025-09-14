<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Aviso/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Aviso;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Aviso\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Aviso as AvisoEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoAviso;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Aviso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|AvisoEntity $avisoEntity;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TokenStorageInterface $tokenStorage;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->avisoEntity = $this->createMock(AvisoEntity::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->rule = new Rule0001(
            $this->tokenStorage,
            $this->rulesTranslate,
            $this->coordenadorService,
            $this->authorizationChecker
        );
    }

    /**
     * @throws RuleException
     */
    public function testRoleAdmin(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->avisoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorSetor(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoAviso = $this->createMock(VinculacaoAviso::class);
        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacaosAvisos = new ArrayCollection();
        $vinculacaosAvisos->add($vinculacaoAviso);

        $this->avisoEntity->expects(self::any())
            ->method('getVinculacoesAvisos')
            ->willReturn($vinculacaosAvisos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorSetor')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->avisoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorUnidade(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn(null);

        $vinculacaoAviso = $this->createMock(VinculacaoAviso::class);
        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaosAvisos = new ArrayCollection();
        $vinculacaosAvisos->add($vinculacaoAviso);

        $this->avisoEntity->expects(self::any())
            ->method('getVinculacoesAvisos')
            ->willReturn($vinculacaosAvisos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->avisoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testCoordenadorOrgaoCentral(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $vinculacaoAviso = $this->createMock(VinculacaoAviso::class);
        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacaosAvisos = new ArrayCollection();
        $vinculacaosAvisos->add($vinculacaoAviso);

        $this->avisoEntity->expects(self::any())
            ->method('getVinculacoesAvisos')
            ->willReturn($vinculacaosAvisos);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->avisoEntity, 'transaction'));
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioVinculado(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

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

        $vinculacaoAviso = $this->createMock(VinculacaoAviso::class);
        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesAvisos = new ArrayCollection();
        $vinculacoesAvisos->add($vinculacaoAviso);

        $this->avisoEntity->expects(self::any())
            ->method('getVinculacoesAvisos')
            ->willReturn($vinculacoesAvisos);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->avisoEntity, 'transaction'));
    }

    public function testUsuarioSemPermissao(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

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

        $vinculacaoAviso = $this->createMock(VinculacaoAviso::class);
        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getSetor')
            ->willReturn($setor);

        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($modalidadeOrgaoCentral);

        $vinculacaoAviso->expects(self::exactly(2))
            ->method('getUsuario')
            ->willReturn($usuarioVinculado);

        $vinculacoesAvisos = new ArrayCollection();
        $vinculacoesAvisos->add($vinculacaoAviso);

        $this->avisoEntity->expects(self::any())
            ->method('getVinculacoesAvisos')
            ->willReturn($vinculacoesAvisos);

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

        $this->rule->validate(null, $this->avisoEntity, 'transaction');
    }
}
