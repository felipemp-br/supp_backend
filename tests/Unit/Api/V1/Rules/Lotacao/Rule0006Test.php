<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Lotacao/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Lotacao\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Lotacao as LotacaoEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Lotacao;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|LotacaoEntity $lotacaoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->lotacaoEntity = $this->createMock(LotacaoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->authorizationChecker,
            $this->coordenadorService
        );
    }

    /**
     * @throws RuleException
     */
    public function testDeleteLotacaoComAdmin(): void
    {
        $this->authorizationChecker->expects(self::exactly(1))
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
    public function testDeleteLotacaoComCoordenadorUnidade(): void
    {
        $unidade = $this->createMock(Setor::class);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->lotacaoEntity->expects(self::once())
            ->method('getSetor')
            ->willReturn($setor);

        $this->coordenadorService->expects(self::any())
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
    public function testDeleteLotacaoComCoordenadorOrgaoCentral(): void
    {
        $orgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($orgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

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

    public function testDeleteLotacaoSemCoordenador(): void
    {
        $orgaoCentral = $this->createMock(ModalidadeOrgaoCentral::class);

        $unidade = $this->createMock(Setor::class);
        $unidade->expects(self::any())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($orgaoCentral);

        $setor = $this->createMock(Setor::class);
        $setor->expects(self::any())
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->lotacaoEntity->expects(self::any())
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

        $this->rule->validate(null, $this->lotacaoEntity, 'transaction');
    }
}
