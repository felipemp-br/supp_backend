<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/CabecalhoField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class CabecalhoField.
 *
 * Renderiza o NUP formatado do processo
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CabecalhoSimplesField implements FieldInterface
{
    private ParameterBagInterface $parameterBag;

    /**
     * RendererManager constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        ParameterBagInterface $parameterBag
    ) {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'cabecalhoSimples';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_cabecalho_simples_field',
                'nome' => 'CABEÇALHO SIMPLES',
                'descricao' => 'CABEÇALHO SIMPLES DE DOCUMENTO',
                'html' => '<span data-method="cabecalhoSimples" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\CabecalhoSimplesField">*cabecalhoSimples*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Documento::class,
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
        $logo = $this->parameterBag->get('supp_core.administrativo_backend.logo_instituicao');

        /** @var Documento $documento */
        $documento = $context['documento'];
        if (!isset($documento) ||
            !$documento->getSetorOrigem() ||
            !$documento->getSetorOrigem()->getUnidade()) {
            return $logo;
        }

        $cabecalho = '';
        $instituicao = $this->parameterBag->get('supp_core.administrativo_backend.nome_instituicao');
        $cabecalho .= $logo.'<br>'.$instituicao;

        /** @var Setor $setor */
        $setor = $documento->getSetorOrigem();

        /** @var Setor $unidade */
        $unidade = $setor->getUnidade();
        /** @var ModalidadeOrgaoCentral $orgaoCentral */
        $orgaoCentral = $unidade->getModalidadeOrgaoCentral();

        if ($orgaoCentral &&
            $orgaoCentral->getDescricao() && $orgaoCentral->getDescricao() !== $instituicao) {
            $cabecalho .= '<br>'.$orgaoCentral->getDescricao();
        }

        return $cabecalho;
    }
}
