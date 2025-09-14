<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/UnidadeField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class UnidadeField.
 *
 * Unidade de Origem do Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class UnidadeField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'unidade';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_unidade_field',
                'nome' => 'UNIDADE',
                'descricao' => 'UNIDADE DE ORIGEM DO DOCUMENTO',
                'html' => '<span data-method="unidade" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\UnidadeField">*unidade*</span>',
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
            !$documento->getSetorOrigem() ||
            !$documento->getSetorOrigem()->getUnidade()) {
            return '';
        }

        return $documento->getSetorOrigem()->getUnidade()->getNome();
    }
}
