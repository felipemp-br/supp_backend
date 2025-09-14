<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ComponenteDigital/Rule0007Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ComponenteDigital;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital\Rule0007;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0007Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ComponenteDigital;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007Test extends TestCase
{
    private MockObject|ComponenteDigitalDto $componenteDigitalDto;

    private MockObject|ComponenteDigitalEntity $componenteDigitalEntity;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->componenteDigitalDto = $this->createMock(ComponenteDigitalDto::class);
        $this->componenteDigitalEntity = $this->createMock(ComponenteDigitalEntity::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0007(
            $this->rulesTranslate
        );
    }

    public function testExtensaoInvalida()
    {
        $this->componenteDigitalDto->expects(self::once())
            ->method('getFileName')
            ->willReturn('arquivo.pdf');

        $this->componenteDigitalDto->expects(self::once())
            ->method('getExtensao')
            ->willReturn('html');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->componenteDigitalDto, $this->componenteDigitalEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testExtensaoValida()
    {
        $this->componenteDigitalDto->expects(self::once())
            ->method('getFileName')
            ->willReturn('arquivo.html');

        $this->componenteDigitalDto->expects(self::once())
            ->method('getExtensao')
            ->willReturn('html');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->componenteDigitalDto, $this->componenteDigitalEntity, 'transaction')
        );
    }
}
