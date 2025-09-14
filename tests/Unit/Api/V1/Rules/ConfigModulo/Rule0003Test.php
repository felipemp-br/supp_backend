<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ConfigModulo/Rule0003Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ConfigModulo;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfigModulo as ConfigModuloDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ConfigModulo\Rule0003;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as ConfigModuloEntity;
use SuppCore\AdministrativoBackend\Mapper\MapperInterface;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0003Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ConfigModulo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003Test extends TestCase
{
    private MockObject|ConfigModuloDto $configModuloDto;

    private MockObject|ConfigModuloEntity $configModuloEntity;

    private MockObject|MapperInterface $mapper;

    private MockObject|MapperManager $mapperManager;

    private MockObject|RulesTranslate $rulesTranslate;

    private MockObject|TransactionManager $transactionManager;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configModuloDto = $this->createMock(ConfigModuloDto::class);
        $this->configModuloEntity = $this->createMock(ConfigModuloEntity::class);
        $this->mapper = $this->createMock(MapperInterface::class);
        $this->mapperManager = $this->createMock(MapperManager::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->rule = new Rule0003(
            $this->rulesTranslate,
            $this->transactionManager,
            $this->mapperManager
        );
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     * @throws Exception
     */
    public function testContextEditAdmin(): void
    {
        $context = $this->createMock(Context::class);
        $context->expects(self::once())
            ->method('getValue')
            ->willReturn(true);

        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn($context);

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoString(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('string');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('test');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoFloat(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('float');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('1.01');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoFloatInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('float');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('test');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoBool(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('bool');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('true');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoBoolInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('bool');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('test');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoInt(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('int');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('11');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoIntInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('int');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('test');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoDatetime(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('datetime');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('2022-01-31T23:00:00');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoDatetimeInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('datetime');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('test');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     */
    public function testTipoJson(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('json');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('{"teste":"123"}');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoJsonInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('json');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('("teste":"123")');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoInvalido(): void
    {
        $this->transactionManager->expects(self::once())
            ->method('getContext')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('mixed');

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn('("teste":"123")');

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $this->configModuloDto->expects(self::once())
            ->method('getDataValue')
            ->willReturn(null);

        $this->mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $this->mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($this->mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }
}
