<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts;

use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * PromptInterface.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface PromptInterface
{
    /**
     * Retorna o texto a ser enviado para a IA.
     *
     * @return string|null
     */
    public function getText(): ?string;

    /**
     * Retorna o documento a ser enviado para a IA.
     *
     * @return Documento|null
     */
    public function getDocumento(): ?Documento;

    /**
     * Retorna o texto da persona do prompt.
     *
     * @return string|null
     */
    public function getPersona(): ?string;
}
