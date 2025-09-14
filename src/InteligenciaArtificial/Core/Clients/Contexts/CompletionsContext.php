<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts;

/**
 * CompletionsContextItem.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompletionsContext
{
    /**
     * Constructor.
     *
     * @param CompletionsContextItem[] $contextList
     *
     */
    public function __construct(
        private array $contextList = []
    ) {
    }

    /**
     * @param CompletionsContextItem $contextItem
     * @return self
     */
    public function addContext(CompletionsContextItem $contextItem): self
    {
        $this->contextList[] = $contextItem;
        return $this;
    }

    /**
     * @return CompletionsContextItem[]
     */
    public function getContext(): array
    {
        return $this->contextList;
    }

    /**
     * @return $this
     */
    public function clearContext(): self
    {
        $this->contextList = [];
        return $this;
    }
}
