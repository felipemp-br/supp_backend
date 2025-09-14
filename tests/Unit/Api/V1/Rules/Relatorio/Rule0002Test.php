<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Relatorio/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Relatorio;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio as RelatorioDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Relatorio\Rule0002;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Relatorio;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|AuthorizationCheckerInterface $authorizationChecker;

    private MockObject|RelatorioDto $relatorioDto;

    private MockObject|RelatorioEntity $relatorioEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TipoRelatorio $tipoRelatorio;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->relatorioDto = $this->createMock(RelatorioDto::class);
        $this->relatorioEntity = $this->createMock(RelatorioEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->tipoRelatorio = $this->createMock(TipoRelatorio::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->authorizationChecker
        );
    }

    public function testUsuarioSemPoderes(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(false);

        $this->relatorioDto->expects(self::once())
            ->method('getTipoRelatorio')
            ->willReturn($this->tipoRelatorio);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->relatorioDto, $this->relatorioEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUsuarioComPoderes(): void
    {
        $this->authorizationChecker->expects(self::once())
            ->method('isGranted')
            ->willReturn(true);

        $this->relatorioDto->expects(self::once())
            ->method('getTipoRelatorio')
            ->willReturn($this->tipoRelatorio);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->relatorioDto, $this->relatorioEntity, 'transaction'));
    }
}
