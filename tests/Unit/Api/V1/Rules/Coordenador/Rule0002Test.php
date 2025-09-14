<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Coordenador/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Coordenador;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Coordenador\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Coordenador as CoordenadorEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Coordenador;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorEntity $coordenadorEntity;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|ModalidadeOrgaoCentral $modalidadeOrgaoCentral;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorEntity = $this->createMock(CoordenadorEntity::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->modalidadeOrgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->authorizationChecker,
            $this->rulesTranslate,
            $this->coordenadorService
        );
    }

    public function testSemAcessoParaVincularCoordenadorDeOrgaoCentral(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComAcessoParaVincularCoordenadorDeOrgaoCentral(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $this->coordenadorEntity->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorEntity->expects(self::once())
            ->method('getSetor')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->coordenadorEntity, 'transaction'));
    }

    public function testSemAcessoParaVincularCoordenadorDeUnidade(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn(null);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComAcessoParaVincularCoordenadorDeUnidade(): void
    {
        $this->authorizationChecker->expects(self::never())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn(null);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->coordenadorEntity->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->coordenadorEntity, 'transaction'));
    }

    public function testSemAcessoParaCriarVinculoNaPropriaUnidadeOuOrgaoCentral(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn(null);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorEntity->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorEntity->expects(self::exactly(3))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->coordenadorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComAcessoParaCriarVinculoNaPropriaUnidadeOuOrgaoCentral(): void
    {
        $this->authorizationChecker->expects(self::never())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorEntity->expects(self::once())
            ->method('getOrgaoCentral')
            ->willReturn(null);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->modalidadeOrgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->coordenadorEntity->expects(self::once())
            ->method('getUnidade')
            ->willReturn(null);

        $this->coordenadorEntity->expects(self::exactly(3))
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->coordenadorEntity, 'transaction'));
    }
}
