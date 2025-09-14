<?php
declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Assunto/Rule0005Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assunto;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Assunto\Rule0005;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0005Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Assunto;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0005Test extends TestCase
{
    private MockObject|AssuntoEntity $assuntoEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assuntoEntity = $this->createMock(AssuntoEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0005(
            $this->rulesTranslate
        );
    }

    public function testAssuntoComOrigemDados(): void
    {
        $origemDados = $this->createMock(OrigemDados::class);
        $this->assuntoEntity->expects(self::exactly(1))
            ->method('getOrigemDados')
            ->willReturn($origemDados);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::any())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->assuntoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testAssuntoSemOrigemDados(): void
    {
        $this->assuntoEntity->expects(self::exactly(1))
            ->method('getOrigemDados')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->assuntoEntity, 'transaction'));
    }
}
