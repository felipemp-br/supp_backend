<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;

/**
 * Class SolicitacaoAutomatizadaSolicitandoDossiesCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SolicitacaoAutomatizadaSolicitandoDossiesCommand extends AbstractSolicitacaoAutomatizadaCommand
{
    public function getName(): ?string
    {
        return 'supp:administrativo:solicitacao_automatizada:solicitando_dossies';
    }

    protected function getCurrentStatus(): StatusSolicitacaoAutomatizada
    {
        return StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES;
    }

    protected function getEtapaProcesso(): string
    {
        return 'verificação de geração de dossies';
    }
}
