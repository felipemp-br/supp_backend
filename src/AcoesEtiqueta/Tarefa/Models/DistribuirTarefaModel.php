<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

/**
 * DistribuirTarefaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class DistribuirTarefaModel
{
    /**
     * Constructor.
     *
     * @param int      $setorResponsavelId
     * @param int      $unidadeResponsavelId
     * @param int|null $usuarioId
     */
    public function __construct(
        private int $setorResponsavelId,
        private int $unidadeResponsavelId,
        private ?int $usuarioId = null
    ) {
    }

    /**
     * Return setorResponsavelId.
     *
     * @return int
     */
    public function getSetorResponsavelId(): int
    {
        return $this->setorResponsavelId;
    }

    /**
     * Return unidadeResponsavelId.
     *
     * @return int
     */
    public function getUnidadeResponsavelId(): int
    {
        return $this->unidadeResponsavelId;
    }

    /**
     * Return usuarioId.
     *
     * @return int|null
     */
    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }
}
