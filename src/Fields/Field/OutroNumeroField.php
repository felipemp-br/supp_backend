<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/OutroNumeroField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class OutroNumeroField.
 *
 * Renderiza o outro número do processo
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class OutroNumeroField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'outroNumero';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_outro_numero_field',
                'nome' => 'OUTRO NUMERO',
                'descricao' => 'OUTRO NUMERO',
                'html' => '<span data-method="outroNumero" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\OutroNumeroField">*outroNumero*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
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
        /** @var Processo $processo */
        $processo = $context['processo'];
        if (!isset($processo) ||
            !$processo->getOutroNumero()) {
            return '';
        }

        return $processo->getOutroNumero();
    }
}
