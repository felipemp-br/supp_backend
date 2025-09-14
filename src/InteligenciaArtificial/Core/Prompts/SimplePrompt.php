<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts;

use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * SimplePrompt.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class SimplePrompt implements PromptInterface
{
    /**
     * Constructor.
     *
     * @param string         $text
     * @param Documento|null $documento
     * @param string|null    $persona
     *
     */
    public function __construct(
        private string $text,
        private ?Documento $documento = null,
        private ?string $persona = null
    ) {
    }

    /**
     * Retorna o texto a ser enviado para a IA.
     *
     * @param array $dadosFormulario
     * @return string
     */
    public function getText(array &$dadosFormulario = []): string
    {
        return $this->text;
    }

    /**
     * Retorna o documento a ser enviado para a IA.
     *
     * @return Documento|null
     */
    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    /**
     * Retorna o texto da persona do prompt.
     *
     * @return string|null
     */
    public function getPersona(): ?string
    {
        return $this->persona;
    }
}
