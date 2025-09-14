<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/EspecieComunicacaoField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class EspecieComunicacaoField.
 *
 * Espécie da comunicação
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class EspecieComunicacaoField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'especieComunicacao';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_especie_comunicacao_field',
                'nome' => 'ESPÉCIE DE COMUNICAÇÃO',
                'descricao' => 'ESPÉCIE DE COMUNICAÇÃO',
                'html' => '<span data-method="especieComunicacao" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\EspecieComunicacaoField">*especieComunicacao*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Documento::class,
            ],
        ];
    }

    /**
     * @param string $transactionId
     * @param array  $context
     * @param array  $options
     *
     * @return string
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        /** @var DocumentoAvulso $documentoAvulso */
        $documentoAvulso = $context['documentoAvulso'];
        if (!isset($documentoAvulso)
            || !$documentoAvulso) {
            return '';
        }

        return $documentoAvulso->getEspecieDocumentoAvulso()->getNome();
    }
}
