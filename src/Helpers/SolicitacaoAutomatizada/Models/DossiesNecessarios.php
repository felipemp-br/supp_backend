<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models;

/**
 * DossiesNecessarios.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DossiesNecessarios
{
    /**
     * Constructor.
     *
     * @param string   $cpf
     * @param string[] $siglasDossies
     */
    public function __construct(
        private string $cpf,
        private array $siglasDossies
    ) {
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @return string[]
     */
    public function getSiglasDossies(): array
    {
        return $this->siglasDossies;
    }
}
