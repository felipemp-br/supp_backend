<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

use SuppCore\AdministrativoBackend\AcoesEtiqueta\Models\IdModel;

/**
 * CriarMinutaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CriarDossieModel
{
    /**
     * Constructor.
     *
     * @param int             $modalidadeInteressadoId
     * @param int[]|IdModel[] $tipoDossie
     * @param int             $visibilidade
     */
    public function __construct(
        private int $modalidadeInteressadoId,
        private array $tipoDossie,
        private int $visibilidade
    ) {
    }

    /**
     * Return modalidadeInteressadoId.
     *
     * @return int
     */
    public function getModalidadeInteressadoId(): int
    {
        return $this->modalidadeInteressadoId;
    }

    /**
     * Return tipoDossie.
     *
     * @return IdModel[]
     */
    public function getTipoDossie(): array
    {
        return array_map(
            fn($id) => $id instanceof IdModel ? $id : new IdModel($id),
            $this->tipoDossie
        );
    }

    /**
     * Return visibilidade.
     *
     * @return int
     */
    public function getVisibilidade(): int
    {
        return $this->visibilidade;
    }
}
