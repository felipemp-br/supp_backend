<?php

declare(strict_types=1);
/**
 * /src/DTO/RestDto.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO;

use JMS\Serializer\Annotation as Serializer;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 * Class RestDto.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class RestDto implements RestDtoInterface, EntityInterface
{
    #[Serializer\SerializedName('@type')]
    protected ?string $jsonLdType = null;

    #[Serializer\SerializedName('@id')]
    protected ?string $jsonLdId = null;

    #[Serializer\SerializedName('@context')]
    protected ?string $jsonLdContext = null;

    #[Serializer\SerializedName('immutable')]
    protected ?bool $immutable = null;

    /**
     * An array of 'visited' setter properties of current dto.
     *
     * @var string[]
     */
    #[Serializer\Exclude]
    protected array $visited = [];

    public function setJsonLdType(?string $jsonLdType): void
    {
        $this->jsonLdType = $jsonLdType;
    }

    public function setJsonLdId(?string $jsonLdId): void
    {
        $this->jsonLdId = $jsonLdId;
    }

    public function setJsonLdContext(?string $jsonLdContext): void
    {
        $this->jsonLdContext = $jsonLdContext;
    }

    public function getJsonLDType(): ?string
    {
        return $this->jsonLdType;
    }

    public function getJsonLDId(): ?string
    {
        return $this->jsonLdId;
    }

    public function getJsonLDContext(): ?string
    {
        return $this->jsonLdContext;
    }

    /**
     * Getter method for visited setters. This is needed for dto patching.
     *
     * @return string[]
     */
    public function getVisited(): array
    {
        return $this->visited;
    }

    /**
     * Setter for visited data. This is needed for dto patching.
     */
    public function setVisited(string $property): RestDtoInterface
    {
        if (!in_array($property, $this->visited, true)) {
            $this->visited[] = $property;
        }

        return $this;
    }

    public function getImmutable(): ?bool
    {
        return $this->immutable;
    }

    public function setImmutable(?bool $immutable): self
    {
        $this->immutable = $immutable;

        return $this;
    }
}
