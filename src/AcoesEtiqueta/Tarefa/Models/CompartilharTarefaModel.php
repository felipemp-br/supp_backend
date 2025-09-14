<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

/**
 * DistribuirTarefaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CompartilharTarefaModel
{
    /**
     * Constructor.
     *
     * @param int|null $setorId
     * @param int|null $usuarioId
     * @param int|null $grupoContatoId
     */
    public function __construct(
        private ?int $setorId = null,
        private ?int $usuarioId = null,
        private ?int $grupoContatoId = null
    ) {
    }

    /**
     * Return setorId.
     *
     * @return int|null
     */
    public function getSetorId(): ?int
    {
        return $this->setorId;
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

    /**
     * Return grupoContatoId.
     *
     * @return int|null
     */
    public function getGrupoContatoId(): ?int
    {
        return $this->grupoContatoId;
    }
}
