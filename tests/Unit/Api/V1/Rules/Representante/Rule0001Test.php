<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Representante/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Representante;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Representante as RepresentanteDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Representante\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Representante as RepresentanteEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Representante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|Interessado $interessado;

    private MockObject|Processo $processo;

    private MockObject|RepresentanteDto $representanteDto;

    private MockObject|RepresentanteEntity $representanteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->interessado = $this->createMock(Interessado::class);
        $this->processo = $this->createMock(Processo::class);
        $this->representanteDto = $this->createMock(RepresentanteDto::class);
        $this->representanteEntity = $this->createMock(RepresentanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderesProcesso(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->interessado->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->representanteDto->expects(self::once())
            ->method('getInteressado')
            ->willReturn($this->interessado);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->representanteDto, $this->representanteEntity, 'transaction');
    }

    public function testUsuarioSemPoderesClassificacao(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->interessado->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->representanteDto->expects(self::once())
            ->method('getInteressado')
            ->willReturn($this->interessado);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->representanteDto, $this->representanteEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoderes(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processo->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->interessado->expects(self::once())
            ->method('getProcesso')
            ->willReturn($this->processo);

        $this->representanteDto->expects(self::once())
            ->method('getInteressado')
            ->willReturn($this->interessado);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->representanteDto, $this->representanteEntity, 'transaction'));
    }
}
