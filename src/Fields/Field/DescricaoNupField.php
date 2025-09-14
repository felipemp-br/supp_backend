<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/DescricaoNupField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class DescricaoNupField.
 *
 * Descrição constante na capa do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DescricaoNupField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'descricaoNup';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_descricao_nup_field',
                'nome' => 'DESCRIÇÃO DO NUP',
                'descricao' => 'DESCRIÇÃO CONSTANTE NA CAPA DO NUP',
                'html' => '<span data-method="descricaoNup" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\DescricaoNupField">*descricaoNup*</span>',
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
            !$processo->getDescricao()) {
            return '';
        }

        return $processo->getDescricao();
    }
}
