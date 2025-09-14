<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/ChaveAcessoField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ChaveAcessoField.
 *
 * Chave de Acesso do NUP
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChaveAcessoField implements FieldInterface
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
        return 'chaveAcesso';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_chave_acesso_field',
                'nome' => 'CHAVE DE ACESSO',
                'descricao' => 'CHAVE DE ACESSO DO NUP',
                'html' => '<span data-method="chaveAcesso" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\ChaveAcessoField">*chaveAcesso*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
            ],
        ];
    }

    /**
     * @param string $transactionId
     * @param array $context
     * @param array $options
     *
     * @return string
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        /** @var Processo $processo */
        $processo = $context['processo'];
        if (!isset($processo) ||
            !$processo->getNUP() ||
            !$processo->getChaveAcesso()) {
            return '';
        }

        $nup = $processo->getNUP();
        $chaveAcesso = $processo->getChaveAcesso();
        $url = $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend');

        return "<hr> <span data-method=\"chaveAcesso\" data-options=\"\" data-service=\"SuppCore\AdministrativoBackend\Fields\Field\ChaveAcessoField\"> 
Atenção, a consulta ao processo eletrônico está disponível em $url mediante o fornecimento do Número Único de Protocolo (NUP) $nup e da chave de acesso $chaveAcesso  </span>";
    }
}
