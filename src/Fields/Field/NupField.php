<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/NupField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class NupField.
 *
 * Renderiza o NUP formatado do processo
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class NupField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'nup';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_nup_field',
                'nome' => 'NUP',
                'descricao' => 'NÚMERO ÚNICO DE PROTOCOLO FORMATADO',
                'html' => '<span data-method="nup" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\NupField">*nup*</span>',
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
            !$processo->getNUP()) {
            return '';
        }

        return $processo->getNUPFormatado();
    }
}
