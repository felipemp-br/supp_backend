<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Models;

/**
 * IdModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class IdModel
{
    /**
     * Constructor.
     *
     * @param int $id
     */
    public function __construct(
        private int $id
    ) {
    }

    /**
     * Return id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
