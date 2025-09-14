<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/DataField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use DateTime;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Utils\DataExtensoService;

/**
 * Class DataField.
 *
 * Local e data do Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DataField implements FieldInterface
{
    private DataExtensoService $dataExtensoService;

    /**
     * DataField constructor.
     *
     * @param DataExtensoService $dataExtensoService
     */
    public function __construct(DataExtensoService $dataExtensoService)
    {
        $this->dataExtensoService = $dataExtensoService;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'data';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_data_field',
                'nome' => 'DATA',
                'descricao' => 'DATA DO DOCUMENTO',
                'html' => '<span data-method="data" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\DataField">*data*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [],
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
        $dateTime = new DateTime();

        return $this->dataExtensoService->dataPorExtenso($dateTime->format('d/m/Y'), '/');
    }
}
