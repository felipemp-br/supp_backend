<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/FinalPrazoTarefaField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;

/**
 * Class FinalPrazoTarefaField.
 *
 * Data do final do prazo da tarefa
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class FinalPrazoTarefaField implements FieldInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'finalPrazoTarefa';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_final_prazo_tarefa_field',
                'nome' => 'DATA DO FINAL DO PRAZO DA TAREFA',
                'descricao' => 'DATA DO FINAL DO PRAZO DA TAREFA',
                'html' => '<span data-method="finalPrazoTarefa" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\FinalPrazoTarefaField">*finalPrazoTarefa*</span>',
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
            !$tarefa->getDataHoraFinalPrazo()) {
            return '';
        }

        return $tarefa->getDataHoraFinalPrazo()->format('d-m-Y');
    }
}
