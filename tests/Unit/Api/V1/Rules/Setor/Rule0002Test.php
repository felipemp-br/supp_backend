<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0002Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0002;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0002Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002Test extends TestCase
{
    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorDto $setorDto;

    private MockObject|SetorEntity $setorEntity;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorDto = $this->createMock(SetorDto::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);

        $this->rule = new Rule0002(
            $this->rulesTranslate,
            $this->parameterBag
        );
    }

    public function testEspecieSetorNaoPermitida(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorDto->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $especieSetorDto = $this->createMock(EspecieSetor::class);
        $especieSetorDto->expects(self::exactly(2))
            ->method('getNome')
            ->willReturn('ARQUIVO');

        $especieSetorDto->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $especieSetorEntity = $this->createMock(EspecieSetor::class);
        $especieSetorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->setorDto->expects(self::exactly(3))
            ->method('getEspecieSetor')
            ->willReturn($especieSetorDto);

        $this->setorEntity->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($especieSetorEntity);

        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('PROTOCOLO', 'ARQUIVO');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->setorDto, $this->setorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testEspecieSetorPermitida(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorDto->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $especieSetorDto = $this->createMock(EspecieSetor::class);
        $especieSetorDto->expects(self::exactly(2))
            ->method('getNome')
            ->willReturn('SECRETARIA');

        $especieSetorDto->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $especieSetorEntity = $this->createMock(EspecieSetor::class);
        $especieSetorEntity->expects(self::once())
            ->method('getId')
            ->willReturn(2);

        $this->setorDto->expects(self::exactly(3))
            ->method('getEspecieSetor')
            ->willReturn($especieSetorDto);

        $this->setorEntity->expects(self::once())
            ->method('getEspecieSetor')
            ->willReturn($especieSetorEntity);

        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('PROTOCOLO', 'ARQUIVO');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->setorDto, $this->setorEntity, 'transaction');
    }

    /**
     * @throws RuleException
     */
    public function testUnidade(): void
    {
        $this->setorDto->expects(self::once())
            ->method('getParent')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }
}
