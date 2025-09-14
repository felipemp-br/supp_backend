<?php

declare(strict_types=1);
/**
 * /tests/Unit/Api/V1/Rules/ConfigModulo/Rule0001Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ConfigModulo;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ConfigModulo as ConfigModuloDto;
use SuppCore\AdministrativoBackend\Api\V1\Rules\ConfigModulo\Rule0001;
use SuppCore\AdministrativoBackend\Entity\ConfigModulo as ConfigModuloEntity;
use SuppCore\AdministrativoBackend\Mapper\MapperInterface;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001Test.
 *
 * @package SuppCore\AdministrativoBackend\Tests\Unit\Api\V1\Rules\ConfigModulo;
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001Test extends TestCase
{
    private MockObject|ConfigModuloDto $configModuloDto;

    private MockObject|ConfigModuloEntity $configModuloEntity;

    private MockObject|MapperManager $mapperManager;

    private MockObject|RulesTranslate $rulesTranslate;

    private RuleInterface $rule;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configModuloDto = $this->createMock(ConfigModuloDto::class);
        $this->configModuloEntity = $this->createMock(ConfigModuloEntity::class);
        $this->mapperManager = $this->createMock(MapperManager::class);
        $this->rulesTranslate = $this->createMock(RulesTranslate::class);

        $this->rule = new Rule0001(
            $this->rulesTranslate,
            $this->mapperManager
        );
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testJsonSemDataSchema(): void
    {
        $this->configModuloDto->expects(self::exactly(2))
            ->method('getDataType')
            ->willReturn('json');

        $this->configModuloDto->expects(self::once())
            ->method('getParadigma')
            ->willReturn(null);

        $this->configModuloDto->expects(self::once())
            ->method('getDataSchema')
            ->willReturn(null);

        $this->configModuloEntity->expects(self::once())
            ->method('getId')
            ->willReturn(1);

        $mapper = $this->createMock(MapperInterface::class);

        $mapper->expects(self::once())
            ->method('createDTOFromEntity')
            ->willReturn($this->configModuloDto);

        $mapper->expects(self::once())
            ->method('patch')
            ->willReturn($this->configModuloDto);

        $this->mapperManager->expects(self::once())
            ->method('getMapper')
            ->willReturn($mapper);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testDataSchemaComJsonInvalido(): void
    {
        $this->configModuloDto->expects(self::exactly(2))
            ->method('getDataType')
            ->willReturn('json');

        $paradigma = $this->createMock(ConfigModuloEntity::class);
        $paradigma->expects(self::once())
            ->method('getDataSchema')
            ->willReturn('{teste:"123"}');

        $this->configModuloDto->expects(self::exactly(2))
            ->method('getParadigma')
            ->willReturn($paradigma);

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }

    /**
     * @throws ReflectionException
     * @throws RuleException
     * @throws Exception
     */
    public function testDataSchemaComJsonValido(): void
    {
        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('json');

        $paradigma = $this->createMock(ConfigModuloEntity::class);
        $paradigma->expects(self::once())
            ->method('getDataSchema')
            ->willReturn('{"teste":"123"}');

        $this->configModuloDto->expects(self::exactly(2))
            ->method('getParadigma')
            ->willReturn($paradigma);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testDatetimeComDataSchema(): void
    {
        $this->configModuloDto->expects(self::exactly(2))
            ->method('getDataType')
            ->willReturn('datetime');

        $this->configModuloDto->expects(self::once())
            ->method('getDataSchema')
            ->willReturn('test');

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
    public function testDatetimeSemDataSchema(): void
    {
        $this->configModuloDto->expects(self::once())
            ->method('getDataType')
            ->willReturn('datetime');

        $this->configModuloDto->expects(self::once())
            ->method('getDataSchema')
            ->willReturn(null);

        $this->rulesTranslate->expects(self::never())
            ->method('throwException')
            ->willThrowException(new RuleException());

        self::assertTrue($this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction'));
    }

    /**
     * @throws ReflectionException
     */
    public function testTipoInvalido(): void
    {
        $this->configModuloDto->expects(self::exactly(2))
            ->method('getDataType')
            ->willReturn('mixed');

        $this->expectException(RuleException::class);
        $this->rulesTranslate->expects(self::once())
            ->method('throwException')
            ->willThrowException(new RuleException());

        $this->rule->validate($this->configModuloDto, $this->configModuloEntity, 'transaction');
    }
}
