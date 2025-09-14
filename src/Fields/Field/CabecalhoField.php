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
class CabecalhoField implements FieldInterface
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
        return 'cabecalho';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_cabecalho_field',
                'nome' => 'CABEÇALHO',
                'descricao' => 'CABEÇALHO DE DOCUMENTO',
                'html' => '<span data-method="cabecalho" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\CabecalhoField">*cabecalho*</span>',
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

        if (!$orgaoCentral) {
            if ($unidade->getNome() != $instituicao) {
                $cabecalho .= '<br>'.$unidade->getNome();
            }
        } else {
            if ($unidade->getNome() != $orgaoCentral->getDescricao()) {
                $cabecalho .= '<br>'.$unidade->getNome();
            }
        }

        if ($setor->getNome() != $unidade->getNome()) {
            $cabecalho .= '<br>'.$setor->getNome();
        }

        $endereco = $setor->getEndereco();

        if ($endereco && '' != $endereco) {
            $cabecalho .= '<br>'.'<span style="font-size: 7pt;">'.$endereco.'<hr /></span>';
        }

        $cabecalho .= '<hr>';

        return $cabecalho;
    }
}
