<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/ProcedenciaField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class ProcedenciaField.
 *
 * Renderiza o nome do órgão de procedência do processo
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcedenciaField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'procedencia';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_procedencia_field',
                'nome' => 'PROCEDENCIA',
                'descricao' => 'PROCEDENCIA',
                'html' => '<span data-method="procedencia" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\ProcedenciaField">*procedencia*</span>',
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
            !$processo->getProcedencia()) {
            return '';
        }

        return $processo->getProcedencia()->getNome();
    }
}
