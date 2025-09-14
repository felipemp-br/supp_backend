<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas;


use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * TrilhaTriagemInput.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class TrilhaTriagemInput
{
    /**
     * Constructor.
     *
     * @param Documento $documento
     * @param array     $context
     * @param bool      $force
     */
    public function __construct(
        public Documento $documento,
        public array $context = [],
        public bool $force = false
    ) {
    }
}
