<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0012Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0012;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0012Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0012Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|CoordenadorService $coordenadorService;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorEntity $setorEntity;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->coordenadorService = $this->createMock(CoordenadorService::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);

        $this->rule = new Rule0012(
            $this->rulesTranslate,
            $this->authorizationChecker,
            $this->coordenadorService
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

        self::assertTrue($this->rule->validate(null, $this->setorEntity, 'transaction'));
    }

    /**
     * @throws Exception
     */
    public function testSemPermissaoParaCriarSetor(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $this->setorEntity->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->setorEntity->expects(self::once())
            ->method('getParent')
            ->willReturn($this->createMock(SetorEntity::class));

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->setorEntity, 'transaction');
    }

    /**
     * @throws Exception
     * @throws RuleException
     */
    public function testComPermissaoParaCriarSetor(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(true);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::once())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $this->setorEntity->expects(self::exactly(2))
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->setorEntity->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn($this->createMock(SetorEntity::class));

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->setorEntity, 'transaction'));
    }

    /**
     * @throws Exception
     */
    public function testSemPermissaoParaUnidade(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(false);

        $this->coordenadorService->expects(self::never())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::never())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $this->setorEntity->expects(self::never())
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->setorEntity->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->setorEntity, 'transaction');
    }

    /**
     * @throws Exception
     * @throws RuleException
     */
    public function testComPermissaoParaUnidade(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->coordenadorService->expects(self::once())
            ->method('verificaUsuarioCoordenadorOrgaoCentral')
            ->willReturn(true);

        $this->coordenadorService->expects(self::never())
            ->method('verificaUsuarioCoordenadorUnidade')
            ->willReturn(false);

        $unidade = $this->createMock(SetorEntity::class);
        $unidade->expects(self::never())
            ->method('getModalidadeOrgaoCentral')
            ->willReturn($this->createMock(ModalidadeOrgaoCentral::class));

        $this->setorEntity->expects(self::never())
            ->method('getUnidade')
            ->willReturn($unidade);

        $this->setorEntity->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->setorEntity, 'transaction'));
    }
}
