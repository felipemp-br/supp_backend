<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Representante/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Representante;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Representante\Rule0002;
use SuppCore\AdministrativoBackend\Entity\OrigemDados;
use SuppCore\AdministrativoBackend\Entity\Representante as RepresentanteEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Representante;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|OrigemDados $origemDados;

    private MockObject|RepresentanteEntity $representanteEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->origemDados = $this->createMock(OrigemDados::class);
        $this->representanteEntity = $this->createMock(RepresentanteEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate
        );
    }

    public function testRepresentanteIntegracao(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('TRF');

        $this->representanteEntity->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate(null, $this->representanteEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testRepresentanteNaoIntegracao(): void
    {
        $this->origemDados->expects(self::once())
            ->method('getFonteDados')
            ->willReturn('SICAU');

        $this->representanteEntity->expects(self::exactly(2))
            ->method('getOrigemDados')
            ->willReturn($this->origemDados);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate(null, $this->representanteEntity, 'transaction'));
    }
}
