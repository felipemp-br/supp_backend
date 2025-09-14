<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Dossie/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Dossie;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Dossie\Rule0001;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Dossie;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|DossieDto $dossieDto;

    private MockObject|DossieEntity $dossieEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dossieDto = $this->createMock(DossieDto::class);
        $this->dossieEntity = $this->createMock(DossieEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
        );
    }

    public function testSemTipoDossie(): void
    {
        $this->dossieDto->expects(self::once())
            ->method('getTipoDossie')
            ->willReturn(null);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testComTipoDossie(): void
    {
        $this->dossieDto->expects(self::once())
            ->method('getTipoDossie')
            ->willReturn($this->createMock(TipoDossie::class));

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->dossieDto, $this->dossieEntity, 'transaction'));
    }
}
