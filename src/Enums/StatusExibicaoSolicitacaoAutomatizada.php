<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * StatusExibicaoSolicitacaoAutomatizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum StatusExibicaoSolicitacaoAutomatizada: string
{
    case EM_PROCESSAMENTO = 'EM PROCESSAMENTO';
    case ACORDO_REALIZADO = 'ACORDO REALIZADO';
    case ACORDO_NAO_REALIZADO = 'ACORDO NÃO REALIZADO';
    case ERRO_SOLICITACAO = 'ERRO NA SOLICITAÇÃO';

    /**
     * @param StatusSolicitacaoAutomatizada $status
     *
     * @return self
     */
    public static function fromStatusSolicitacao(StatusSolicitacaoAutomatizada $status): self
    {
        return match ($status) {
            StatusSolicitacaoAutomatizada::CRIADA,
            StatusSolicitacaoAutomatizada::SOLICITANDO_DOSSIES,
            StatusSolicitacaoAutomatizada::ANDAMENTO,
            StatusSolicitacaoAutomatizada::ANALISANDO_REQUISITOS,
            StatusSolicitacaoAutomatizada::ANALISE_PROCURADOR,
            StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO,
                => self::EM_PROCESSAMENTO,
            StatusSolicitacaoAutomatizada::DEFERIDO,
            StatusSolicitacaoAutomatizada::DADOS_CUMPRIMENTO,
            StatusSolicitacaoAutomatizada::AGUARDANDO_CUMPRIMENTO,
            StatusSolicitacaoAutomatizada::SOLICITACAO_ATENDIDA,
                => self::ACORDO_REALIZADO,
            StatusSolicitacaoAutomatizada::INDEFERIDO,
            StatusSolicitacaoAutomatizada::SOLICITACAO_NAO_ATENDIDA,
                => self::ACORDO_NAO_REALIZADO,
            default => self::ERRO_SOLICITACAO,
        };
    }
}
