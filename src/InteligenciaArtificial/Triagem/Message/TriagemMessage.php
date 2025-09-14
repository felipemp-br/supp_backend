<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Message;

/**
 * TriagemMessage.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TriagemMessage
{
    /**
     * Constructor.
     *
     * @param string $documentoUuid
     * @param array  $context
     * @param bool   $force
     *
     */
    public function __construct(
        public string $documentoUuid,
        public array $context = [],
        public bool $force = false
    ) {
    }

    /**
     * @return string
     */
    public function getDocumentoUuid(): string
    {
        return $this->documentoUuid;
    }

    /**
     * @param string $documentoUuid
     * @return $this
     */
    public function setDocumentoUuid(string $documentoUuid): self
    {
        $this->documentoUuid = $documentoUuid;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return $this
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return bool
     */
    public function getForce(): bool
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return $this
     */
    public function setForce(bool $force): self
    {
        $this->force = $force;

        return $this;
    }
}
