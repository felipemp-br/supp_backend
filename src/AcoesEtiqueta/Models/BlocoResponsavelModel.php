<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Models;

/**
 * BlocoResponsavelModel.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class BlocoResponsavelModel
{
    /**
     * Constructor.
     *
     * @param int    $id
     * @param string $tipo
     */
    public function __construct(
        private int $id,
        private string $tipo
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

    /**
     * Return tipo.
     *
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }
}
