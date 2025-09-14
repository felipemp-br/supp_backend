<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SolicitacaoAutomatizadaDriverInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * SolicitacaoAutomatizadaDriverManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class SolicitacaoAutomatizadaDriverManager
{
    /** @var SolicitacaoAutomatizadaDriverInterface[] */
    private array $drivers;

    public function __construct(
        #[AutowireIterator('supp_core.administrativo_backend.solicitacao_automatizada.driver')]
        iterable $drivers
    ) {
        $this->drivers = iterator_to_array($drivers);
    }

    /**
     * Retorna o driver da solicitação automatizada.
     *
     * @param TipoSolicitacaoAutomatizada   $tipoSolicitacaoAutomatizada
     * @param StatusSolicitacaoAutomatizada $statusSolicitacaoAutomatizada
     *
     * @return SolicitacaoAutomatizadaDriverInterface|null
     */
    public function getDriver(
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        StatusSolicitacaoAutomatizada $statusSolicitacaoAutomatizada
    ): ?SolicitacaoAutomatizadaDriverInterface {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($tipoSolicitacaoAutomatizada, $statusSolicitacaoAutomatizada)) {
                return $driver;
            }
        }

        return null;
    }
}
