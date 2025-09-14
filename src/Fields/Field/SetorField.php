<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/SetorField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class SetorField.
 *
 * Setor de Origem do Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class SetorField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'setor';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_setor_field',
                'nome' => 'SETOR',
                'descricao' => 'SETOR DE ORIGEM DO DOCUMENTO',
                'html' => '<span data-method="setor" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\SetorField">*setor*</span>',
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
            !$documento->getSetorOrigem()) {
            return '';
        }

        return $documento->getSetorOrigem()->getNome();
    }
}
