<?php

declare(strict_types=1);
/**
 * /src/Rest/Message/PushMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest\Message;

/**
 * Class PushMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PushMessage
{
    private ?string $channel = null;

    private ?string $uuid = null;

    private ?string $operation = 'AddData';

    // para o caso de addChildData
    private ?string $parentType = null;

    private ?int $parentId = null;

    private ?string $resource = null;

    private array $populate = [];

    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setOperation(?string $operation): void
    {
        $this->operation = $operation;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(?string $resource): void
    {
        $this->resource = $resource;
    }

    public function getPopulate(): array
    {
        return $this->populate;
    }

    public function setPopulate(array $populate): void
    {
        $this->populate = $populate;
    }

    public function getParentType(): ?string
    {
        return $this->parentType;
    }

    public function setParentType(?string $parentType): void
    {
        $this->parentType = $parentType;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }
}
