<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Processo/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Processo\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Classificacao;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Processo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|ProcessoDto $processoDto;

    private MockObject|ProcessoEntity $processoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->processoDto = $this->createMock(ProcessoDto::class);
        $this->processoEntity = $this->createMock(ProcessoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testSemPoderesMaster(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->processoDto->expects(self::once())
            ->method('getVisibilidadeExterna')
            ->willReturn(false);

        $this->processoEntity->expects(self::once())
            ->method('getVisibilidadeExterna')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->processoDto, $this->processoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComPoderesMaster(): void
    {
        $this->authorizationChecker->expects(self::exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $this->processoDto->expects(self::once())
            ->method('getVisibilidadeExterna')
            ->willReturn(false);

        $classificacao = $this->createMock(Classificacao::class);
        $classificacao->expects(self::once())
            ->method('getId')
            ->willReturn(10);

        $this->processoEntity->expects(self::exactly(3))
            ->method('getClassificacao')
            ->willReturn($classificacao);

        $this->processoEntity->expects(self::once())
            ->method('getVisibilidadeExterna')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->processoDto, $this->processoEntity, 'transaction'));
    }
}
