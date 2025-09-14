<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * StatusSolicitacaoAutomatizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum StatusSolicitacaoAutomatizada: string
{
    case CRIADA = 'CRIADA';
    case SOLICITANDO_DOSSIES = 'SOLICITANDO DOSSIES';
    case ANDAMENTO = 'EM ANDAMENTO';
    case ANALISANDO_REQUISITOS = 'ANALISANDO REQUISITOS';
    case ANALISE_PROCURADOR = 'ANÁLISE PROCURADOR';
    case DEFERIDO = 'DEFERIDO';
    case INDEFERIDO = 'INDEFERIDO';
    case DADOS_CUMPRIMENTO = 'DADOS CUMPRIMENTO';
    case AGUARDANDO_CUMPRIMENTO = 'AGUARDANDO CUMPRIMENTO';
    case SOLICITACAO_ATENDIDA = 'SOLICITAÇÃO ATENDIDA';
    case SOLICITACAO_NAO_ATENDIDA = 'SOLICITAÇÃO NÃO ATENDIDA';
    case ERRO_SOLICITACAO = 'ERRO NA SOLICITAÇÃO';
}
