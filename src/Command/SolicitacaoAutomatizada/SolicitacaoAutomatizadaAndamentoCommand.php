<?php

declare(strict_types=1);
/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Command\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Command\Traits\SymfonyStyleTrait;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;

/**
 * Class SolicitacaoAutomatizadaAndamentoCommand.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SolicitacaoAutomatizadaAndamentoCommand extends AbstractSolicitacaoAutomatizadaCommand
{
    use SymfonyStyleTrait;

    public function getName(): ?string
    {
        return 'supp:administrativo:solicitacao_automatizada:andamento';
    }

    protected function getCurrentStatus(): StatusSolicitacaoAutomatizada
    {
        return StatusSolicitacaoAutomatizada::ANDAMENTO;
    }

    protected function getEtapaProcesso(): string
    {
        return 'verificação de andamento';
    }
}
