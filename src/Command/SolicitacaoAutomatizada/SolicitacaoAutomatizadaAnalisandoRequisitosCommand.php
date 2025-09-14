<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;

/**
 * Class SolicitacaoAutomatizadaAnalisandoRequisitosCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SolicitacaoAutomatizadaAnalisandoRequisitosCommand extends AbstractSolicitacaoAutomatizadaCommand
{
    public function getName(): ?string
    {
        return 'supp:administrativo:solicitacao_automatizada:analisando_requisitos';
    }

    protected function getCurrentStatus(): StatusSolicitacaoAutomatizada
    {
        return StatusSolicitacaoAutomatizada::ANALISANDO_REQUISITOS;
    }

    protected function getEtapaProcesso(): string
    {
        return 'verificação de requisitos';
    }
}
