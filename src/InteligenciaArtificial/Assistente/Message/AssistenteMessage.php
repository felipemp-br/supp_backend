<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Message;

/**
 * Class AssistenteMessage.
 */
class AssistenteMessage
{
    public function __construct(
        protected readonly string $channel,
        protected readonly string $uuid,
        protected readonly ?string $userPrompt = null,
        protected readonly ?string $actionPrompt = null,
        protected readonly ?int $documentoId = null,
        protected readonly bool $rag = false,
        protected readonly array $context = [],
        protected readonly ?string $persona = null,
    ) {
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserPrompt(): ?string
    {
        return $this->userPrompt;
    }

    public function getActionPrompt(): ?string
    {
        return $this->actionPrompt;
    }

    public function getDocumentoId(): ?int
    {
        return $this->documentoId;
    }

    public function getRag(): bool
    {
        return $this->rag;
    }

    public function getPersona(): ?string
    {
        return $this->persona;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
