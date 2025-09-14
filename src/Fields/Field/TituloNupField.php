<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/TituloNupField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class TituloNupField.
 *
 * Título constante na capa do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TituloNupField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'tituloNup';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_titulo_nup_field',
                'nome' => 'TÍTULO DO NUP',
                'descricao' => 'TÍTULO CONSTANTE NA CAPA DO NUP',
                'html' => '<span data-method="tituloNup" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\TituloNupField">*tituloNup*</span>',
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
            !$processo->getTitulo()) {
            return '';
        }

        return $processo->getTitulo();
    }
}
