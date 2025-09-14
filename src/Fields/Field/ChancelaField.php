<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/ChancelaField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\DividaBackend\Api\V1\Resource\ChancelaResource;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * Class ChancelaField.
 *
 * Assinatura em texto definida pelo Usuário.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ChancelaField implements FieldInterface
{

    /**
     * ChancelaField constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'chancelaUsuario';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_assinatura_usuario_field',
                'nome' => 'CHANCELA DO USUÁRIO',
                'descricao' => 'IMAGEM DA CHANCELA DEFINIDA PELO USUÁRIO',
                'html' => '<span data-method="chancelaUsuario" data-options="" data-service="SuppCore\AdministrativoBackend\Fields\Field\ChancelaField">*chancelaUsuario*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
            ],
        ];
    }

    /**
     * @param string $transactionId
     * @param array $context
     * @param array $options
     *
     * @return array|null
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {   /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()->getUser();

        $componenteDigitalChancela = $usuario->getImgChancela();
        $assinaturaHtml = $usuario->getAssinaturaHTML();
        $chancelaEncoded = "";

        if ($componenteDigitalChancela) {
            $chancelaEncoded = base64_encode($componenteDigitalChancela->getConteudo());
        }

        return <<<EOF
        <div class="cda-assinatura" style="text-align:center;">
        <br>
        <img src='data:image/jpeg;base64,{{ $chancelaEncoded|raw }}' alt=''/>
        <br>
        {{ $assinaturaHtml|raw }}
        </div>
        EOF;
    }
}
