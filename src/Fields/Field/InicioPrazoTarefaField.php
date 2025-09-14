<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/InicioPrazoTarefaField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class InicioPrazoTarefaField.
 *
 * Data do Inicio do prazo da tarefa
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class InicioPrazoTarefaField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'inicioPrazoTarefa';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_inicio_prazo_tarefa_field',
                'nome' => 'DATA DO INÍCIO DO PRAZO DA TAREFA',
                'descricao' => 'DATA DO INÍCIO DO PRAZO DA TAREFA',
                'html' => '<span data-method="inicioPrazoTarefa" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\InicioPrazoTarefaField">*inicioPrazoTarefa*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Tarefa::class,
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
        /** @var Tarefa $tarefa */
        $tarefa = $context['tarefa'];
        if (!isset($tarefa) ||
            !$tarefa ||
            !$tarefa->getDataHoraInicioPrazo()) {
            return '';
        }

        return $tarefa->getDataHoraInicioPrazo()->format('d-m-Y');
    }
}
