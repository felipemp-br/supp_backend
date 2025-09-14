<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0006Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0006;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0006Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006Test extends TestCase
{
    private MockObject|ProcessoRepository $processoRepository;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorDto $setorDto;

    private MockObject|SetorEntity $setorEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->processoRepository = $this->createMock(ProcessoRepository::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorDto = $this->createMock(SetorDto::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);

        $this->rule = new Rule0006(
            $this->rulesTranslate,
            $this->processoRepository
        );
    }

    public function testSetorComProcessos(): void
    {
        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->setorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $processo = $this->createMock(Processo::class);
        $this->processoRepository->expects(self::once())
            ->method('findImpedeInativacao')
            ->willReturn($processo);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->setorDto, $this->setorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testSetorSemProcessos(): void
    {
        $this->setorDto->expects(self::once())
            ->method('getAtivo')
            ->willReturn(false);

        $this->setorEntity->expects(self::once())
            ->method('getAtivo')
            ->willReturn(true);

        $this->setorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->processoRepository->expects(self::once())
            ->method('findImpedeInativacao')
            ->willReturn(false);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }
}
