<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts;

/**
 * CompletionsContextItem.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompletionsContextItem
{
    /**
     * Constructor.
     *
     * @param string $role
     * @param string $content
     *
     */
    public function __construct(
        private readonly string $role,
        private readonly string $content,
    ) {
    }

    /**
     * Retorna a role de execução.
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Retorna o conteúdo da execução.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
