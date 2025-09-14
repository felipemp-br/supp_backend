<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Assistente\Models;

use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * AssistentePrompt.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AssistentePrompt
{
    public function __construct(
        protected readonly ?string $userPrompt = null,
        protected readonly ?string $actionPrompt = null,
        protected readonly ?Documento $documento = null,
        protected readonly bool $rag = false,
        protected readonly array $context = [],
        protected readonly ?string $persona = null,
    ) {
    }

    public function getUserPrompt(): ?string
    {
        return $this->userPrompt;
    }

    public function getActionPrompt(): ?string
    {
        return $this->actionPrompt;
    }

    public function getDocumento(): ?Documento
    {
        return $this->documento;
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
