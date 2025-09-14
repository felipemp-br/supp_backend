<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * SolicitacaoAutomatizadaDriverInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.solicitacao_automatizada.driver')]
interface SolicitacaoAutomatizadaDriverInterface
{
    /**
     * Processa a mudança de status da solicitacao automatizada.
     *
     * @param SolicitacaoAutomatizadaEntity $entity
     * @param SolicitacaoAutomatizadaDTO    $dto
     * @param string                        $transactionId
     *
     * @return void
     */
    public function processaStatus(
        SolicitacaoAutomatizadaEntity $entity,
        SolicitacaoAutomatizadaDTO $dto,
        string $transactionId
    ): void;

    /**
     * Verifica se o handler suporta o tipo de solicitacao automatizada.
     *
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     *
     * @return bool
     */
    public function supports(
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
    ): bool;
}
