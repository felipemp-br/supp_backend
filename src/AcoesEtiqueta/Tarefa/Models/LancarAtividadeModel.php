<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

/**
 * LancarAtividadeModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class LancarAtividadeModel
{
    /**
     * Constructor.
     *
     * @param int         $usuarioId
     * @param string      $destinacaoMinutas
     * @param bool        $encerraTarefa
     * @param string|null $observacao
     * @param int|null    $especieAtividadeId
     * @param int[]       $especiesAtividadeId
     * @param int|null    $setorAprovacaoId
     * @param int|null    $unidadeAprovacaoId
     * @param int|null    $usuarioAprovacaoId
     */
    public function __construct(
        private int $usuarioId,
        private string $destinacaoMinutas,
        private ?bool $encerraTarefa = false,
        private ?string $observacao = null,
        private ?int $especieAtividadeId = null,
        private array $especiesAtividadeId = [],
        private ?int $setorAprovacaoId = null,
        private ?int $unidadeAprovacaoId = null,
        private ?int $usuarioAprovacaoId = null,
    ) {
    }

    /**
     * Return usuarioId.
     *
     * @return int
     */
    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }

    /**
     * Return destinacaoMinutas.
     *
     * @return string
     */
    public function getDestinacaoMinutas(): string
    {
        return $this->destinacaoMinutas;
    }

    /**
     * Return encerraTarefa.
     *
     * @return bool
     */
    public function getEncerraTarefa(): bool
    {
        return $this->encerraTarefa ?? false;
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
     * Return especieAtividadeId.
     *
     * @return int|null
     */
    public function getEspecieAtividadeId(): ?int
    {
        return $this->especieAtividadeId;
    }

    /**
     * Return especiesAtividadeId.
     *
     * @return int[]
     */
    public function getEspeciesAtividadeId(): array
    {
        return $this->especiesAtividadeId;
    }

    /**
     * Return setorAprovacaoId.
     *
     * @return int|null
     */
    public function getSetorAprovacaoId(): ?int
    {
        return $this->setorAprovacaoId;
    }

    /**
     * Return unidadeAprovacaoId.
     *
     * @return int|null
     */
    public function getUnidadeAprovacaoId(): ?int
    {
        return $this->unidadeAprovacaoId;
    }

    /**
     * Return usuarioAprovacaoId.
     *
     * @return int|null
     */
    public function getUsuarioAprovacaoId(): ?int
    {
        return $this->usuarioAprovacaoId;
    }
}
