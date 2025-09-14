<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models;

/**
 * CriarMinutaModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class CriarMinutaModel
{
    /**
     * Constructor.
     *
     * @param int $modeloId
     */
    public function __construct(
        private int $modeloId
    ) {
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
}
