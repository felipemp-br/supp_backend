<?php

declare(strict_types=1);
/**
 * /src//Mapper/MapperMetadata.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Mapper;

use SuppCore\AdministrativoBackend\Mapper\Attributes\JsonLD as JsonLDAttribute;
use SuppCore\AdministrativoBackend\Mapper\Attributes\Mapper as MapperAttribute;
use SuppCore\AdministrativoBackend\Mapper\Attributes\Property as PropertyAttribute;

/**
 * Class MapperMetadata.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class MapperMetadata
{
    /**
     * @var null|MapperAttribute;
     */
    protected ?MapperAttribute $mapper = null;

    /**
     * @var null|JsonLDAttribute;
     */
    protected ?JsonLDAttribute $jsonLD = null;

    /**
     * @var PropertyAttribute[]
     */
    protected array $properties = [];

    /**
     * @param MapperAttribute $mapper
     *
     * @return $this
     */
    public function setMapper(MapperAttribute $mapper): self
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * @return MapperAttribute|null
     */
    public function getMapper(): ?MapperAttribute
    {
        return $this->mapper;
    }

    /**
     * @return JsonLDAttribute|null
     */
    public function getJsonLD(): ?JsonLDAttribute
    {
        return $this->jsonLD;
    }

    /**
     * @param JsonLDAttribute|null $jsonLD
     */
    public function setJsonLD(?JsonLDAttribute $jsonLD): void
    {
        $this->jsonLD = $jsonLD;
    }

    /**
     * @param PropertyAttribute $property
     *
     * @return MapperMetadata
     */
    public function addProperty(PropertyAttribute $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return PropertyAttribute[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
