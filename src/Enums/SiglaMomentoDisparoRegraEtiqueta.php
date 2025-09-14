<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * SiglaMomentoDisparoRegraEtiqueta.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum SiglaMomentoDisparoRegraEtiqueta: string
{
    case TAREFA_DISTRIBUICAO = 'TAREFA_DISTRIBUICAO';
    case PROCESSO_PRIMEIRA_TAREFA = 'PROCESSO_PRIMEIRA_TAREFA';
    case PROCESSO_DISTRIBUICAO = 'PROCESSO_DISTRIBUICAO';
    case PROCESSO_CRIACAO_PROCESSO_ADMINISTRATIVO = 'PROCESSO_CRIACAO_PROCESSO_ADMINISTRATIVO';

    public function isEqual(string $sigla): bool
    {
        return $this->value === $sigla;
    }
}
