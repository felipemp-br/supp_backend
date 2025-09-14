<?php

declare(strict_types=1);
/**
 * /src//Mapper/MapperManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper;

use Psr\Cache\InvalidArgumentException;
use SuppCore\AdministrativoBackend\Mapper\Attributes\Property;
use SuppCore\AdministrativoBackend\Mapper\Driver\AttributesDriver;

use function array_key_exists;
use function get_class;

/**
 * Class MapperManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MapperManager
{
    /**
     * @var MapperInterface[]
     */
    protected array $mappers = [];

    protected array $mapperConfig = [];

    /**
     * MapperManager constructor.
     *
     * @param AttributesDriver $attributesDriver
     */
    public function __construct(
        private readonly AttributesDriver $attributesDriver
    ) {
    }

    /**
     * @return array
     */
    public function getMapperConfig(): array
    {
        return $this->mapperConfig;
    }

    /**
     * @param array $mapperConfig
     */
    public function setMapperConfig(array $mapperConfig): void
    {
        $this->mapperConfig = $mapperConfig;
    }

    /**
     * @param MapperInterface $mapper
     */
    public function addMapper(MapperInterface $mapper): void
    {
        $this->mappers[get_class($mapper)] = $mapper;
    }

    /**
     * @return MapperInterface
     */
    public function getDefaultMapper(): MapperInterface
    {
        return $this->mappers[DefaultMapper::class];
    }

    /**
     * @param string $dtoClassName
     *
     * @return MapperInterface
     * @throws InvalidArgumentException
     */
    public function getMapper(string $dtoClassName): MapperInterface
    {
        $mapperMetadata = $this->getMapperMetadata($dtoClassName);
        if ($mapperMetadata->getMapper()
            && array_key_exists($mapperMetadata->getMapper()->class, $this->mappers)) {
            return $this->mappers[$mapperMetadata->getMapper()->class];
        }

        return $this->mappers[DefaultMapper::class];
    }

    /**
     * @param string $dtoClassName
     * @param string $propertyName
     *
     * @return Property|null
     * @throws InvalidArgumentException
     */
    public function getPropertyDTO(string $dtoClassName, string $propertyName): ?Property
    {
        $mapperMetadata = $this->getMapperMetadata($dtoClassName);
        foreach ($mapperMetadata->getProperties() as $property) {
            if ($property->name === $propertyName) {
                return $property;
            }
        }

        return null;
    }

    /**
     * @param string $dtoClassName
     *
     * @return MapperMetadata
     * @throws InvalidArgumentException
     */
    public function getMapperMetadata(string $dtoClassName): MapperMetadata
    {
        return $this->attributesDriver->getMetadata($dtoClassName);
    }
}
