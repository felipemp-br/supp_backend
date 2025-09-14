<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

/**
 * CriarTarefaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CriarTarefaModel
{
    /**
     * Constructor.
     *
     * @param int         $especieTarefaId
     * @param int         $setorResponsavelId
     * @param int         $unidadeResponsavelId
     * @param int         $setorOrigemId
     * @param int         $prazoDias
     * @param bool        $urgente
     * @param bool        $diasUteis
     * @param string|null $observacao
     * @param int|null    $usuarioResponsavelId
     */
    public function __construct(
        private int $especieTarefaId,
        private int $setorResponsavelId,
        private int $unidadeResponsavelId,
        private int $setorOrigemId,
        private int $prazoDias,
        //private bool $urgente = false,
        private ?bool $urgente = false,
        private ?bool $diasUteis = false,
        private ?string $observacao = null,
        private ?int $usuarioResponsavelId = null,
    ) {
    }

    /**
     * Return especieTarefaId.
     *
     * @return int
     */
    public function getEspecieTarefaId(): int
    {
        return $this->especieTarefaId;
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
     * Return setorOrigemId.
     *
     * @return int
     */
    public function getSetorOrigemId(): int
    {
        return $this->setorOrigemId;
    }

    /**
     * Return prazoDias.
     *
     * @return int
     */
    public function getPrazoDias(): int
    {
        return $this->prazoDias;
    }

    /**
     * Return urgente.
     *
     * @return bool
     */
    public function getUrgente(): bool
    {
        //return $this->urgente;
        return $this->urgente ?? false; // <-- ALTERAÇÃO AQUI (coalesce null para false)
    }

    /**
     * Return diasUteis.
     *
     * @return bool|null
     */
    public function getDiasUteis(): ?bool
    {
        return $this->diasUteis;
    }

    /**
     * Return observacao.
     *
     * @return string|null
     */
    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    /**
     * Return usuarioResponsavelId.
     *
     * @return int|null
     */
    public function getUsuarioResponsavelId(): ?int
    {
        return $this->usuarioResponsavelId;
    }
}
