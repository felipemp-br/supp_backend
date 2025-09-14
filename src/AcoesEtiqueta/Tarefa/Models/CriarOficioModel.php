<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

use SuppCore\AdministrativoBackend\AcoesEtiqueta\Models\BlocoResponsavelModel;

/**
 * CriarMinutaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CriarOficioModel
{
    /**
     * Constructor.
     *
     * @param int                     $especieDocumentoAvulsoId
     * @param int                     $modeloId
     * @param int                     $prazoFinal
     * @param string                  $mecanismoRemessa
     * @param bool                    $urgente
     * @param int|null                $pessoaDestinoId
     * @param int|null                $setorDestinoId
     * @param int|null                $setorOrigemId
     * @param BlocoResponsavelModel[] $blocoResponsaveis
     * @param string|null             $observacao
     */
    public function __construct(
        private int $especieDocumentoAvulsoId,
        private int $modeloId,
        private int $prazoFinal,
        private string $mecanismoRemessa,
        private bool $urgente = false,
        private ?int $pessoaDestinoId = null,
        private ?int $setorDestinoId = null,
        private ?int $setorOrigemId = null,
        private array $blocoResponsaveis = [],
        private ?string $observacao = null
    ) {
    }

    /**
     * Return especieDocumentoAvulsoId.
     *
     * @return int
     */
    public function getEspecieDocumentoAvulsoId(): int
    {
        return $this->especieDocumentoAvulsoId;
    }

    /**
     * Return modeloId.
     *
     * @return int
     */
    public function getModeloId(): int
    {
        return $this->modeloId;
    }

    /**
     * Return prazoFinal.
     *
     * @return int
     */
    public function getPrazoFinal(): int
    {
        return $this->prazoFinal;
    }

    /**
     * Return mecanismoRemessa.
     *
     * @return string
     */
    public function getMecanismoRemessa(): string
    {
        return $this->mecanismoRemessa;
    }

    /**
     * Return pessoaDestinoId.
     *
     * @return int|null
     */
    public function getPessoaDestinoId(): ?int
    {
        return $this->pessoaDestinoId;
    }

    /**
     * Return setorDestinoId.
     *
     * @return int|null
     */
    public function getSetorDestinoId(): ?int
    {
        return $this->setorDestinoId;
    }

    /**
     * Return setorOrigemId.
     *
     * @return int|null
     */
    public function getSetorOrigemId(): ?int
    {
        return $this->setorOrigemId;
    }

    /**
     * Return blocoResponsaveis.
     *
     * @return BlocoResponsavelModel[]
     */
    public function getBlocoResponsaveis(): array
    {
        return $this->blocoResponsaveis;
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
     * Return urgente.
     *
     * @return bool
     */
    public function getUrgente(): bool
    {
        return $this->urgente;
    }
}
