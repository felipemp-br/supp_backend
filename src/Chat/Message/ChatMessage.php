<?php

declare(strict_types=1);
namespace SuppCore\AdministrativoBackend\Chat\Message;

/**
 * Class ChatMessage
 * @package SuppCore\AdministrativoBackend\Chat\Message
 */
class ChatMessage
{

    /**
     * ChatMessage constructor.
     * @param array|null $chanels
     * @param string|null $resource
     * @param string|null $uuid
     * @param array|null $populate
     * @param array|null $contexts
     */
    public function __construct(public ?array $chanels = [],
                                public ?string $resource = null,
                                public ?string $uuid = null,
                                public ?array $populate = [],
                                public ?array $contexts = [])
    {
    }

    /**
     * @return array|null
     */
    public function getChanels(): ?array
    {
        return $this->chanels;
    }

    /**
     * @param array|null $chanels
     * @return ChatMessage
     */
    public function setChanels(?array $chanels): ChatMessage
    {
        $this->chanels = $chanels;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * @param string|null $resource
     * @return ChatMessage
     */
    public function setResource(?string $resource): ChatMessage
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     * @return ChatMessage
     */
    public function setUuid(?string $uuid): ChatMessage
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPopulate(): ?array
    {
        return $this->populate;
    }

    /**
     * @param array|null $populate
     * @return ChatMessage
     */
    public function setPopulate(?array $populate): ChatMessage
    {
        $this->populate = $populate;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getContexts(): ?array
    {
        return $this->contexts;
    }

    /**
     * @param array|null $contexts
     * @return ChatMessage
     */
    public function setContexts(?array $contexts): ChatMessage
    {
        $this->contexts = $contexts;

        return $this;
    }
}
