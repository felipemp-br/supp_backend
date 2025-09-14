<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/LocalDataField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use DateTime;
use SuppCore\AdministrativoBackend\Entity\Lotacao;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Utils\DataExtensoService;

/**
 * Class LocalDataField.
 *
 * Local e data do Documento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LocalDataField implements FieldInterface
{
    private DataExtensoService $dataExtensoService;

    private LotacaoRepository $lotacaoRepository;

    /**
     * LocalDataField constructor.
     *
     * @param DataExtensoService $dataExtensoService
     * @param LotacaoRepository  $lotacaoRepository
     */
    public function __construct(DataExtensoService $dataExtensoService,
                                LotacaoRepository $lotacaoRepository)
    {
        $this->dataExtensoService = $dataExtensoService;
        $this->lotacaoRepository = $lotacaoRepository;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'localData';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_local_data_field',
                'nome' => 'LOCAL E DATA',
                'descricao' => 'LOCAL E DATA DO DOCUMENTO',
                'html' => '<span data-method="localData" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\LocalDataField">*localData*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Usuario::class,
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
        /** @var Usuario $usuario */
        $usuario = $context['usuario'];
        if (!isset($usuario) ||
            !$usuario->getColaborador()) {
            return '';
        }

        $dateTime = new DateTime();

        /** @var Lotacao $lotacaoPrincipal */
        $lotacaoPrincipal = $this->lotacaoRepository->findLotacaoPrincipal($usuario->getColaborador()->getId());

        if (!$lotacaoPrincipal ||
            !$lotacaoPrincipal->getSetor() ||
            !$lotacaoPrincipal->getSetor()->getMunicipio()) {
            return '';
        } else {
            $nomes = [];
            $partes = explode(' ', $lotacaoPrincipal->getSetor()->getMunicipio()->getNome());
            foreach ($partes as $parte) {
                if (in_array(mb_strtoupper($parte, 'UTF-8'), ['DO', 'DA', 'DOS', 'DAS', 'DE'])) {
                    $nomes[] = mb_strtolower($parte, 'UTF-8');
                } else {
                    $nomes[] = $this->dataExtensoService->mb_ucfirst($parte);
                }
            }

            return implode(' ', $nomes).', '.$this->dataExtensoService->dataPorExtenso($dateTime->format('d/m/Y'), '/').'.';
        }
    }
}
