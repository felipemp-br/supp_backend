<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/EspecieProcesso/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\EspecieProcesso;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\EspecieProcesso\Rule0001;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\GeneroProcesso;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\EspecieProcesso;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|EspecieProcessoDto $especieProcessoDto;

    private MockObject|EspecieProcessoEntity $especieProcessoEntity;

    private MockObject|EspecieProcessoRepository $especieProcessoRepository;

    private MockObject|GeneroProcesso $generoProcesso;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->especieProcessoDto = $this->createMock(EspecieProcessoDto::class);
        $this->especieProcessoEntity = $this->createMock(EspecieProcessoEntity::class);
        $this->especieProcessoRepository = $this->createMock(EspecieProcessoRepository::class);
        $this->generoProcesso = $this->createMock(GeneroProcesso::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->especieProcessoRepository
        );
    }

    /**
     * @throws RuleException
     */
    public function testNomeUnico(): void
    {
        $this->especieProcessoDto->expects(self::once())
            ->method('getNome')
            ->willReturn('TESTE');

        $this->especieProcessoDto->expects(self::once())
            ->method('getGeneroProcesso')
            ->willReturn($this->generoProcesso);

        $this->especieProcessoRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->especieProcessoDto, $this->especieProcessoEntity, 'transaction'));
    }

    public function testNomeDuplicado(): void
    {
        $this->especieProcessoDto->expects(self::once())
            ->method('getNome')
            ->willReturn('TESTE');

        $this->especieProcessoDto->expects(self::once())
            ->method('getGeneroProcesso')
            ->willReturn($this->generoProcesso);

        $this->especieProcessoDto->expects(self::once())
            ->method('getId')
            ->willReturn(12);

        $especieProcesso = $this->createMock(EspecieProcessoEntity::class);
        $especieProcesso->expects(self::once())
            ->method('getId')
            ->willReturn(21);

        $this->especieProcessoRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($especieProcesso);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->especieProcessoDto, $this->especieProcessoEntity, 'transaction');
    }
}
