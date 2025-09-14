<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Selectors;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;

/**
 * TipoDocumentoSelector.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TipoDocumentoSelector extends BaseProcessoDocumentoSelector implements SelectorInterface
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
                'ultimo_documento_tipo',
                'primeiro_documento_tipo',
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
        if ('ultimo_documento_tipo' === $parsedExpression['seletor_documento']) {
            $documentos = array_reverse($documentos);
        }

        foreach ($documentos as $documento) {
            if ($documento->getTipoDocumento()->getId() === (int) $parsedExpression['tipo_documento']) {
                return $documento;
            }
        }

        return null;
    }
}
