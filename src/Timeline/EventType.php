<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Timeline;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Type Event Class.
 */
class EventType extends RestDto
{
    #[Serializer\Exclude]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $id = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $name = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $action = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $objectClass = null;

    #[OA\Property(type: 'integer')]
    protected ?int $objectId = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->setVisited('name');
        $this->name = $name;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->setVisited('action');
        $this->action = $action;

        return $this;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectClass(?string $objectClass): self
    {
        $this->setVisited('objectClass');
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    public function setObjectId(?int $objectId): self
    {
        $this->setVisited('objectId');
        $this->objectId = $objectId;

        return $this;
    }
}
