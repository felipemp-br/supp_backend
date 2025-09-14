<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/AssuntoAdministrativo/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\AssuntoAdministrativo;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo as AssuntoAdministrativoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\AssuntoAdministrativo\Rule0001;
use SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo as AssuntoAdministrativoEntity;
use SuppCore\AdministrativoBackend\Repository\AssuntoAdministrativoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\AssuntoAdministrativo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|AssuntoAdministrativoDto $assuntoAdministrativoDto;

    private MockObject|AssuntoAdministrativoEntity $assuntoAdministrativoEntity;

    private MockObject|AssuntoAdministrativoRepository $assuntoAdministrativoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->assuntoAdministrativoDto = $this->createMock(AssuntoAdministrativoDto::class);
        $this->assuntoAdministrativoEntity = $this->createMock(AssuntoAdministrativoEntity::class);
        $this->assuntoAdministrativoRepository = $this->createMock(AssuntoAdministrativoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->assuntoAdministrativoRepository,
        );
    }

    public function testAssuntoComFilhos(): void
    {
        $this->assuntoAdministrativoDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->assuntoAdministrativoEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->assuntoAdministrativoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(99);

        $this->assuntoAdministrativoRepository->expects(self::once())
            ->method('hasFilhosAtivos')
            ->willReturn(true);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->assuntoAdministrativoDto, $this->assuntoAdministrativoEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testAssuntoSemFilhos(): void
    {
        $this->assuntoAdministrativoDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->assuntoAdministrativoEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->assuntoAdministrativoEntity->expects(self::once())
            ->method('getId')
            ->willReturn(99);

        $this->assuntoAdministrativoRepository->expects(self::once())
            ->method('hasFilhosAtivos')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue(
            $this->rule->validate($this->assuntoAdministrativoDto, $this->assuntoAdministrativoEntity, 'transaction')
        );
    }
}
