<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Selectors;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * EspecieTarefaSelector.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieTarefaSelector extends BaseProcessoDocumentoSelector implements SelectorInterface
{
    /**
     * @param string   $expression
     * @param Processo $processo
     *
     * @return bool
     */
    public function support(string $expression, Processo $processo): bool
    {
        $parsedExpression = [];
        parse_str($expression, $parsedExpression);

        return in_array(
            $parsedExpression['seletor_documento'],
            [
                'ultima_tarefa_tipo',
                'primeira_tarefa_tipo',
            ]
        );
    }

    /**
     * @param string   $expression
     * @param Processo $processo
     *
     * @return Documento|null
     */
    public function getDocumento(string $expression, Processo $processo): ?Documento
    {
        $parsedExpression = [];
        parse_str($expression, $parsedExpression);
        $documentos = $this->getDocumentosProcesso($processo);
        if ('ultima_tarefa_tipo' === $parsedExpression['seletor_documento']) {
            $documentos = array_reverse($documentos);
        }
        foreach ($documentos as $documento) {
            $especieTarefaId = $documento->getJuntadaAtual()?->getTarefa()?->getEspecieTarefa()?->getId();
            if ($especieTarefaId === (int) $parsedExpression['especie_tarefa']) {
                return $documento;
            }
        }

        return null;
    }
}
