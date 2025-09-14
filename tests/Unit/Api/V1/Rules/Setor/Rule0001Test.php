<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/Setor/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\Setor\Rule0001;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\Setor;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ParameterBagInterface $parameterBag;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|SetorDto $setorDto;

    private MockObject|SetorEntity $setorEntity;

    private MockObject|TransactionManager $transactionManager;

    private RuleInterface $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->setorDto = $this->createMock(SetorDto::class);
        $this->setorEntity = $this->createMock(SetorEntity::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->transactionManager,
            $this->parameterBag
        );
    }

    public function testEspecieSetorNaoPermitida(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorDto->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $especieSetor = $this->createMock(EspecieSetor::class);
        $especieSetor->expects(self::exactly(2))
            ->method('getNome')
            ->willReturn('ARQUIVO');

        $this->setorDto->expects(self::exactly(2))
            ->method('getEspecieSetor')
            ->willReturn($especieSetor);

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

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $especieSetor = $this->createMock(EspecieSetor::class);
        $especieSetor->expects(self::exactly(2))
            ->method('getNome')
            ->willReturn('SECRETARIA');

        $this->setorDto->expects(self::exactly(2))
            ->method('getEspecieSetor')
            ->willReturn($especieSetor);

        $this->parameterBag->expects(self::exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls('PROTOCOLO', 'ARQUIVO');

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
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

    /**
     * @throws RuleException
     */
    public function testCriacaoUnidade(): void
    {
        $parent = $this->createMock(SetorEntity::class);
        $this->setorDto->expects(self::once())
            ->method('getParent')
            ->willReturn($parent);

        $context = $this->createMock(Context::class);
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->setorDto, $this->setorEntity, 'transaction'));
    }
}
