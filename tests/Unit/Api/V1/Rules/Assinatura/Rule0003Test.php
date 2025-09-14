<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assinatura/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura\Rule0003;
use SuppCore\AdministrativoBackend\Entity\Assinatura as AssinaturaEntity;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assinatura;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AssinaturaDto $assinaturaDto;

    private MockObject|AssinaturaEntity $assinaturaEntity;

    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ComponenteDigital $componenteDigital;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assinaturaDto = $this->createMock(AssinaturaDto::class);
        $this->assinaturaEntity = $this->createMock(AssinaturaEntity::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->componenteDigital = $this->createMock(ComponenteDigital::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoder(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->componenteDigital->expects(self::once())
            ->method('getDocumento')
            ->willReturn(new Documento());

        $this->assinaturaDto->expects(self::once())
            ->method('getComponenteDigital')
            ->willReturn($this->componenteDigital);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoder(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->componenteDigital->expects(self::once())
            ->method('getDocumento')
            ->willReturn(new Documento());

        $this->assinaturaDto->expects(self::once())
            ->method('getComponenteDigital')
            ->willReturn($this->componenteDigital);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->assinaturaDto, $this->assinaturaEntity, 'transaction'));
    }
}
