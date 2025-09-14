<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/ValorEconomicoField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class ValorEconomicoField.
 *
 * Descrição constante na capa do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ValorEconomicoField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'valorEconomico';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_valor_economico_field',
                'nome' => 'VALOR ECONÔMICO',
                'descricao' => 'VALOR ECONÔMICO DO PROCESSO',
                'html' => '<span data-method="valorEconomico" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\ValorEconomicoField">*valorEconomico*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
            ],
        ];
    }

    /**
     * @param string $transactionId
     * @param array $context
     * @param array $options
     *
     * @return string|null
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        /** @var Processo $processo */
        $processo = $context['processo'];
        if (!isset($processo) ||
            !$processo->getValorEconomico()) {
            return '';
        }

        return (string) $processo->getValorEconomico();
    }
}
