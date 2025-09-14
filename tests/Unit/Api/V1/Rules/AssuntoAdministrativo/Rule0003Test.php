<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Administrativo/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\AssuntoAdministrativo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\AssuntoAdministrativo\Rule0003;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo as AssuntoAdministrativoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\AssuntoAdministrativo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|AssuntoAdministrativoEntity $assuntoAdministrativoEntity;

    private MockObject|AssuntoAdministrativoEntity $assuntoAdministrativoPai;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assuntoAdministrativoEntity = $this->createMock(AssuntoAdministrativoEntity::class);
        $this->assuntoAdministrativoPai = $this->createMock(AssuntoAdministrativoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate
        );
    }

    public function testAssuntoPaiInativo(): void
    {
        $this->assuntoAdministrativoPai->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->assuntoAdministrativoEntity->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn($this->assuntoAdministrativoPai);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->assuntoAdministrativoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testAssuntoPaiAtivo(): void
    {
        $this->assuntoAdministrativoPai->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->assuntoAdministrativoEntity->expects(self::exactly(2))
            ->method('getParent')
            ->willReturn($this->assuntoAdministrativoPai);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->assuntoAdministrativoEntity, 'transaction'));
    }
}
