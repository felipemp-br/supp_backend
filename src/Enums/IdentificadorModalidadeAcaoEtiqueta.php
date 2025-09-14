<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Enums;

/**
 * IdentificadorModalidadeAcaoEtiqueta.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
enum IdentificadorModalidadeAcaoEtiqueta: string
{
    case TAREFA_CRIAR_MINUTA = 'TAREFA_CRIAR_MINUTA';
    case TAREFA_DISTRIBUIR_TAREFA = 'TAREFA_DISTRIBUIR_TAREFA';
    case TAREFA_COMPARTILHAR_TAREFA = 'TAREFA_COMPARTILHAR_TAREFA';
    case TAREFA_CRIAR_OFICIO = 'TAREFA_CRIAR_OFICIO';
    case TAREFA_CRIAR_DOSSIE = 'TAREFA_CRIAR_DOSSIE';
    case TAREFA_CRIAR_TAREFA = 'TAREFA_CRIAR_TAREFA';
    case TAREFA_LANCAR_ATIVIDADE = 'TAREFA_LANCAR_ATIVIDADE';

    public function isEqual(string $sigla): bool
    {
        return $this->value === $sigla;
    }
}
