<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/NumeroDocumentoField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class NumeroDocumentoField.
 *
 * Número do Documento Formatado
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NumeroDocumentoField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'numeroDocumento';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_num_documento_field',
                'nome' => 'NÚMERO DO DOCUMENTO',
                'descricao' => 'NÚMERO DO DOCUMENTO FORMATADO',
                'html' => '<span data-method="numeroDocumento" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\NumeroDocumentoField">*numeroDocumento*</span>',
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
        /** @var Documento $documento */
        $documento = $context['documento'];
        if (!isset($documento) ||
            !$documento->getNumeroUnicoDocumento() ||
            !$documento->getNumeroUnicoDocumento()->geraNumeroUnico()) {
            return '';
        }

        return $documento->getNumeroUnicoDocumento()->geraNumeroUnico();
    }
}
