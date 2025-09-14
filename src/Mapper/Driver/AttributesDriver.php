<?php

declare(strict_types=1);
/**
 * /src//Mapper/Driver/AttributesDriver.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper\Driver;

use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use SuppCore\AdministrativoBackend\Mapper\Attributes\JsonLD as JsonLDAttribute;
use SuppCore\AdministrativoBackend\Mapper\Attributes\Mapper as MapperAttribute;
use SuppCore\AdministrativoBackend\Mapper\Attributes\Property as PropertyAttribute;
use SuppCore\AdministrativoBackend\Mapper\MapperMetadata;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Class AttributesDriver.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class AttributesDriver implements MetadataDriverInterface
{
    /**
     * @param CacheInterface $appCache
     */
    public function __construct(
        private CacheInterface $appCache
    ) {
    }

    /**
     * @param string $dtoClassName
     *
     * @return MapperMetadata
     *
     * @throws InvalidArgumentException
     */
    public function getMetadata(string $dtoClassName): MapperMetadata
    {
        return $this->appCache->get('mapper_'.str_replace('\\', '_', $dtoClassName), function () use ($dtoClassName) {
            $metadata = new MapperMetadata();
            $reflectionClass = new ReflectionClass($dtoClassName);

            foreach ($reflectionClass->getAttributes() as $attribute) {
                if (MapperAttribute::class === $attribute->getName()) {
                    $metadata->setMapper($attribute->newInstance());
                }

                if (JsonLDAttribute::class === $attribute->getName()) {
                    $metadata->setJsonLD($attribute->newInstance());
                }
            }

            while (is_object($reflectionClass)) {
                $properties = $reflectionClass->getProperties();
                foreach ($properties as $property) {
                    /** @var PropertyAttribute $propertyMetadata */
                    $propertyMetadata = null;
                    foreach ($property->getAttributes() as $attribute) {
                        if (PropertyAttribute::class === $attribute->getName()) {
                            $propertyMetadata = $attribute->newInstance();
                            break;
                        }
                    }
                    if (!$propertyMetadata) {
                        continue;
                    }
                    if (null === $propertyMetadata->name) {
                        $propertyMetadata->name = $property->getName();
                    }
                    $metadata->addProperty($propertyMetadata);
                }
                $reflectionClass = $reflectionClass->getParentClass();
            }

            return $metadata;
        });
    }
}
