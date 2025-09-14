<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts\TrilhaTriagemPromptInterface;

/**
 * BaseTrilhaTriagemPrompt.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class BaseTrilhaTriagemPrompt implements TrilhaTriagemPromptInterface
{
    private array $dadosFormulario = [];
    protected ?Documento $documento = null;
    protected ?string $persona = null;
    protected ?TrilhaTriagemInput $input = null;

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

    /**
     * Seta os dados formulario para passagem de contexto.
     *
     * @param array $triagemData
     * @return self
     */
    public function setDadosFormulario(array $triagemData): TrilhaTriagemPromptInterface
    {
        $this->dadosFormulario = $triagemData;
        return $this;
    }

    /**
     * Retorna os dados do formulário.
     *
     * @return array
     */
    protected function getDadosFormulario(): array
    {
        return $this->dadosFormulario;
    }

    /**
     * Seta o input da trilha de triagem para caso o prompt precise da informação.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return self
     */
    public function setTrilhaTriagemInput(TrilhaTriagemInput $input): self
    {
        $this->input = $input;
        return $this;
    }
}
