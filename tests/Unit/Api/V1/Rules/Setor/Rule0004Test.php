<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0004Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0004;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004Test extends TestCase
{
    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorDto $setorDto;

    private MockObject|SetorEntity $setorEntity;

    private MockObject|SetorRepository $setorRepository;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorDto = $this->createMock(SetorDto::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);
        $this->setorRepository = $this->createMock(SetorRepository::class);

        $this->rule = new Rule0004(
            $this->rulesTranslate,
            $this->setorRepository
        );
    }

    public function testDesativarComFilhos(): void
    {
        $this->setorRepository->expects(self::once())
            ->method('findFilhos')
            ->willReturn([[]]);

        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->setorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->setorDto, $this->setorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testDesativarSemFilhos(): void
    {
        $this->setorRepository->expects(self::once())
            ->method('findFilhos')
            ->willReturn(null);

        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->setorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }
}
