<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Attributes;

use Attribute;

/**
 * Trilha.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class Trilha
{
    /**
     * Constructor.
     *
     * @param string   $nome Descrição do contexto do prompt.
     * @param array    $prompts   Lista da classname das prompts a serem executadas nesse contexto.
     * @param string[] $dependsOn Lista da classname das trilhas que precisam ser executadas antes da trilha atual.
     *
     */
    public function __construct(
        private string $nome,
        private array $prompts = [],
        private array $dependsOn = []
    ) {
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return string[]
     */
    public function getPrompts(): array
    {
        return $this->prompts;
    }

    /**
     * @return string[]
     */
    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }
}
