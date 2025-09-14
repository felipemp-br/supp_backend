<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Cronjob/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Cronjob;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as CronjobDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Cronjob\Rule0001;
use SuppCore\AdministrativoBackend\Cronjob\CronjobExpressionServiceInterface;
use SuppCore\AdministrativoBackend\Entity\Cronjob as CronjobEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Cronjob;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|CronjobDto $cronjobDto;

    private MockObject|CronjobEntity $cronjobEntity;

    private MockObject|CronjobExpressionServiceInterface $cronjobExpressionService;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cronjobDto = $this->createMock(CronjobDto::class);
        $this->cronjobEntity = $this->createMock(CronjobEntity::class);
        $this->cronjobExpressionService = $this->createMock(CronjobExpressionServiceInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->cronjobExpressionService
        );
    }

    /**
     * @throws RuleException
     */
    public function testPeriodicidadeValida(): void
    {
        $this->cronjobDto->expects(self::once())
            ->method('getPeriodicidade')
            ->willReturn('* */5 * * *');

        $this->cronjobExpressionService->expects(self::once())
            ->method('isValid')
            ->willReturn(true);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->cronjobDto, $this->cronjobEntity, 'transaction'));
    }

    public function testPeriodicidadeInvalida(): void
    {
        $this->cronjobDto->expects(self::once())
            ->method('getPeriodicidade')
            ->willReturn('*');

        $this->cronjobExpressionService->expects(self::once())
            ->method('isValid')
            ->willReturn(false);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->cronjobDto, $this->cronjobEntity, 'transaction');
    }
}
